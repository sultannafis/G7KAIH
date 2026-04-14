<?php

namespace App\Services\G7KAIH;

use App\Models\HabitSubmission;
use App\Models\HabitValidation;
use App\Models\User;
use App\Services\AI\AiValidationService;
use App\Services\Notification\NotificationService;

class HabitValidationService
{
    public function __construct(
        protected NotificationService  $notificationService,
        protected AiValidationService  $aiValidationService,
    ) {}

    /**
     * Orang tua menyetujui submission anak.
     * Jika rule aktif AI validasi → pending_ai → jalankan AI
     * Jika tidak → pending_teacher (flow lama)
     */
    public function parentApprove(HabitSubmission $submission, User $parent): void
    {
        $this->log($submission, 'parent', $parent, 'approved');

        // Cek apakah perlu AI validasi
        if ($this->aiValidationService->shouldValidate($submission)) {
            $submission->update(['status' => 'pending_ai']);
            $this->runAiValidation($submission);
        } else {
            $submission->update(['status' => 'pending_teacher']);
            $this->notifyTeacher($submission);
        }

        $this->sendValidationNotification($submission, 'ParentValidationApproved', recipients: ['student']);
    }

    /**
     * Orang tua menolak submission anak.
     */
    public function parentReject(HabitSubmission $submission, User $parent, string $reason): void
    {
        $this->log($submission, 'parent', $parent, 'rejected', $reason);
        $submission->update(['status' => 'parent_rejected', 'point' => 0]);
        $this->sendValidationNotification($submission, 'ParentValidationRejected',
            extraVars: ['rejection_reason' => $reason], recipients: ['student']);
    }

    /**
     * Guru menyetujui — bisa dari pending_teacher ATAU ai_valid.
     * KEPUTUSAN FINAL. Poin dikunci.
     */
    public function teacherApprove(HabitSubmission $submission, User $teacher, ?int $overridePoint = null): void
    {
        $this->log($submission, 'teacher', $teacher, 'approved');

        $finalPoint = $overridePoint ?? $submission->rule?->point ?? 0;

        $submission->update([
            'status'          => 'teacher_valid',
            'point'           => $finalPoint,
            'ai_needs_review' => false,
        ]);

        $submission->load('student');
        $submission->student->recalculateTotalPoint();

        $totalPoint = $submission->student->total_point;
        $this->sendValidationNotification($submission, 'HabitApproved', extraVars: [
            'submission_point' => (string) $finalPoint,
            'total_point'      => (string) $totalPoint,
        ], recipients: ['student', 'parents']);
    }

    /**
     * Guru menolak — bisa dari pending_teacher ATAU override ai_valid.
     * WAJIB isi alasan. Poin direset.
     */
    public function teacherReject(HabitSubmission $submission, User $teacher, string $reason): void
    {
        $this->log($submission, 'teacher', $teacher, 'rejected', $reason);
        $submission->update([
            'status'          => 'teacher_rejected',
            'point'           => 0,
            'ai_needs_review' => false,
        ]);

        $submission->load('student');
        $submission->student->recalculateTotalPoint();

        $this->sendValidationNotification($submission, 'HabitRejected',
            extraVars: ['rejection_reason' => $reason], recipients: ['student', 'parents']);
    }

    // ─── AI Flow ──────────────────────────────────────────────────────────

    private function runAiValidation(HabitSubmission $submission): void
    {
        try {
            $result = $this->aiValidationService->validate($submission);
            $this->aiValidationService->applyResult($submission, $result);

            // Refresh setelah applyResult update model
            $submission->refresh();

            // Notifikasi guru: ada submission masuk (dari AI)
            $this->notifyTeacher($submission);

        } catch (\Throwable $e) {
            \Log::error('AI Validation failed, fallback to teacher: ' . $e->getMessage());
            // Fallback: langsung ke guru seperti biasa
            $submission->update(['status' => 'pending_teacher']);
            $this->notifyTeacher($submission);
        }
    }

    private function notifyTeacher(HabitSubmission $submission): void
    {
        try {
            $this->sendValidationNotification($submission, 'TeacherValidationRequired', recipients: ['teacher']);
        } catch (\Throwable $e) {
            \Log::warning('Gagal notif guru: ' . $e->getMessage());
        }
    }

    // ─── Shared Notification Helper ───────────────────────────────────────

    protected function sendValidationNotification(
        HabitSubmission $submission,
        string          $event,
        array           $extraVars  = [],
        array           $recipients = [],
    ): void {
        $submission->loadMissing([
            'student.user',
            'student.parents.user',
            'student.g7kaihClass.teacher',
            'habit',
            'habitItem',
            'selectedActivities',
        ]);

        $student     = $submission->student;
        $studentUser = $student->user;
        $schoolId    = $studentUser->school_id;

        $submissionDate = $submission->submission_date
            ? $submission->submission_date->translatedFormat('d F Y')
            : now()->translatedFormat('d F Y');

        if ($submission->habitItem === null && $submission->selectedActivities->isNotEmpty()) {
            $habitItemName = $submission->selectedActivities->pluck('name')->join(', ');
        } else {
            $habitItemName = $submission->habitItem?->name ?? '-';
        }

        $baseVars = array_merge([
            'student_name'     => $studentUser->name,
            'habit_name'       => $submission->habit->name,
            'habit_item_name'  => $habitItemName,
            'submission_date'  => $submissionDate,
            'submission_point' => (string) ($submission->point ?? 0),
            'total_point'      => (string) ($student->total_point ?? 0),
        ], $extraVars);

        if (in_array('student', $recipients)) {
            $this->notificationService->send($event, $studentUser, $baseVars, $schoolId);
        }

        if (in_array('parents', $recipients)) {
            foreach ($student->parents as $parent) {
                $parentUser = $parent->user;
                $this->notificationService->send($event, $parentUser,
                    array_merge($baseVars, [
                        'parent_name'     => $parentUser->name,
                        'parent_relation' => $parent->family_relationship ?? '-',
                    ]), $schoolId);
            }
        }

        if (in_array('teacher', $recipients)) {
            $teacherUser = $student->g7kaihClass?->teacher;
            if ($teacherUser) {
                $this->notificationService->send($event, $teacherUser,
                    array_merge($baseVars, ['teacher_name' => $teacherUser->name]), $schoolId);
            }
        }
    }

    protected function log(HabitSubmission $submission, string $type, ?User $user, string $status, ?string $reason = null): void
    {
        HabitValidation::create([
            'habit_submission_id' => $submission->id,
            'validator_type'      => $type,
            'validator_id'        => $user?->id,
            'status'              => $status,
            'reason'              => $reason,
        ]);
    }
}
