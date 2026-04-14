<?php

namespace App\Services\G7KAIH;

use App\Models\Habit;
use App\Models\HabitItem;
use App\Models\HabitRule;
use App\Models\HabitSubmission;
use App\Models\Student;
use App\Services\Notification\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HabitSubmissionService
{
    public function __construct(
        protected NotificationService $notificationService,
    ) {}

    /**
     * Buat submission habit baru oleh siswa (dengan media file / foto).
     */
    public function submit(
        Student    $student,
        Habit      $habit,
        ?HabitItem $habitItem,
        ?HabitRule $habitRule,
        Carbon     $submittedAt,
        int        $mediaFileId,
        ?string    $description = null,
    ): HabitSubmission {
        return DB::transaction(function () use (
            $student, $habit, $habitItem, $habitRule,
            $submittedAt, $mediaFileId, $description
        ) {
            $g7kaihClassId = $student->g7_kaih_class_id;

            if ($g7kaihClassId) {
                $g7kaihClass = $student->g7kaihClass;
                if ($g7kaihClass && !$g7kaihClass->is_active) {
                    $g7kaihClassId = null;
                }
            }

            $submission = HabitSubmission::create([
                'student_id'       => $student->id,
                'g7_kaih_class_id' => $g7kaihClassId,
                'habit_id'         => $habit->id,
                'habit_item_id'    => $habitItem?->id,
                'habit_rule_id'    => $habitRule?->id,
                'submission_date'  => $submittedAt->toDateString(),
                'submitted_at'     => $submittedAt,
                'description'      => $description,
                'status'           => 'pending_parent',
                'point'            => 0,
            ]);

            $submission->mediaFiles()->attach($mediaFileId);

            return $submission;
        });
    }

    /**
     * Buat submission habit tanpa media file — khusus habit berbasis WAKTU.
     *
     * Tidak butuh foto karena waktu submit sudah jadi bukti otomatis.
     * Poin langsung diset dari rule yang berlaku saat itu.
     */
    public function submitWithoutMedia(
        Student    $student,
        Habit      $habit,
        ?HabitItem $habitItem,
        ?HabitRule $habitRule,
        Carbon     $submittedAt,
        ?string    $description = null,
    ): HabitSubmission {
        return DB::transaction(function () use (
            $student, $habit, $habitItem, $habitRule, $submittedAt, $description
        ) {
            $g7kaihClassId = $student->g7_kaih_class_id;

            if ($g7kaihClassId) {
                $g7kaihClass = $student->g7kaihClass;
                if ($g7kaihClass && !$g7kaihClass->is_active) {
                    $g7kaihClassId = null;
                }
            }

            return HabitSubmission::create([
                'student_id'       => $student->id,
                'g7_kaih_class_id' => $g7kaihClassId,
                'habit_id'         => $habit->id,
                'habit_item_id'    => $habitItem?->id,
                'habit_rule_id'    => $habitRule?->id,
                'submission_date'  => $submittedAt->toDateString(),
                'submitted_at'     => $submittedAt,
                'description'      => $description,
                'status'           => 'pending_parent',
                'point'            => $habitRule?->point ?? 0,
            ]);
        });
    }

    /**
     * Kirim notifikasi setelah submission dibuat.
     *
     * Events yang dikirim:
     *  - HabitSubmitted         → siswa + semua orangtua
     *  - ParentValidationRequired → semua orangtua
     */
    public function sendHabitSubmittedNotification(HabitSubmission $submission): void
    {
        // Load semua relasi yang dibutuhkan sekaligus
        $submission->load([
            'student.user',
            'student.parents.user',
            'habit',
            'habitItem',
            'selectedActivities', // untuk multi-select habit
        ]);

        $student     = $submission->student;
        $studentUser = $student->user;
        $schoolId    = $studentUser->school_id;

        $studentName = $studentUser->name;
        $habitName   = $submission->habit->name;

        // Multi-select: item ada di pivot selectedActivities, bukan di habitItem
        if ($submission->habitItem === null && $submission->selectedActivities->isNotEmpty()) {
            $habitItemName = $submission->selectedActivities->pluck('name')->join(', ');
        } else {
            $habitItemName = $submission->habitItem?->name ?? '-';
        }

        $submissionDate = $submission->submission_date
            ? $submission->submission_date->translatedFormat('d F Y')
            : now()->translatedFormat('d F Y');

        // ── Notifikasi ke setiap orangtua ────────────────────────
        foreach ($student->parents as $parent) {
            $parentUser     = $parent->user;
            $parentName     = $parentUser->name;
            $parentRelation = $parent->family_relationship ?? '-';

            $parentVars = [
                'student_name'    => $studentName,
                'habit_name'      => $habitName,
                'habit_item_name' => $habitItemName,
                'submission_date' => $submissionDate,
                'parent_name'     => $parentName,
                'parent_relation' => $parentRelation,
            ];

            // HabitSubmitted → orangtua
            $this->notificationService->send(
                'HabitSubmitted',
                $parentUser,
                $parentVars,
                $schoolId
            );

            // ParentValidationRequired → orangtua
            $this->notificationService->send(
                'ParentValidationRequired',
                $parentUser,
                $parentVars,
                $schoolId
            );
        }

        // ── Notifikasi ke siswa ───────────────────────────────────
        $this->notificationService->send(
            'HabitSubmitted',
            $studentUser,
            [
                'student_name'    => $studentName,
                'habit_name'      => $habitName,
                'habit_item_name' => $habitItemName,
                'submission_date' => $submissionDate,
            ],
            $schoolId
        );
    }

    /**
     * Ambil submission harian siswa.
     */
    public function getTodaySubmissions(Student $student, string $date): \Illuminate\Support\Collection
    {
        return HabitSubmission::where('student_id', $student->id)
            ->where('submission_date', $date)
            ->with(['habit', 'habitItem', 'rule', 'validations', 'mediaFiles'])
            ->get();
    }

    /**
     * Cek apakah siswa sudah submit habit tertentu hari ini.
     */
    public function alreadySubmittedToday(
        Student    $student,
        Habit      $habit,
        ?HabitItem $habitItem,
        string     $date
    ): bool {
        return HabitSubmission::where('student_id', $student->id)
            ->where('habit_id', $habit->id)
            ->where('habit_item_id', $habitItem?->id)
            ->where('submission_date', $date)
            ->exists();
    }

    /**
     * Kunci submission hari sebelumnya (via cron job harian).
     */
    public function lockPreviousDaySubmissions(string $date): int
    {
        return HabitSubmission::where('submission_date', '<', $date)
            ->whereIn('status', ['pending_parent', 'pending_teacher'])
            ->update(['status' => 'teacher_rejected', 'point' => 0]);
    }
}