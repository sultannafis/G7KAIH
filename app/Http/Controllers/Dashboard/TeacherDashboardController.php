<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Habit;
use App\Models\HabitSubmission;
use App\Models\HabitValidation;
use App\Models\Student;
use Carbon\Carbon;

class TeacherDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user    = Auth::user();
        $teacher = $user->teacher;

        $myClassIds = DB::table('g7_kaih_classes')
            ->where('teacher_id', $user->id)
            ->where('is_active', true)
            ->pluck('id');

        $pendingValidations = HabitSubmission::whereIn('g7_kaih_class_id', $myClassIds)
            ->where('status', 'pending_teacher')
            ->count();

        $approvedToday = HabitValidation::where('validator_type', 'teacher')
            ->where('validator_id', $user->id)
            ->where('status', 'approved')
            ->whereDate('created_at', now())
            ->count();

        // ── Total siswa di kelas guru ──────────────────────────────────────
        $totalStudents = Student::whereIn('g7_kaih_class_id', $myClassIds)->count();

        // ── Submission terbaru yang perlu divalidasi (untuk tabel inline) ──
        $recentPendingSubmissions = HabitSubmission::whereIn('g7_kaih_class_id', $myClassIds)
            ->where('status', 'pending_teacher')
            ->with(['habit', 'habitItem', 'student.user', 'mediaFiles'])
            ->latest('submitted_at')
            ->take(8)
            ->get();

        // ── Siswa butuh perhatian (7 hari terakhir) ────────────────────────
        // PERUBAHAN: Kedisiplinan dihitung dari SEMUA habit aktif yang ada,
        // bukan hanya habit yang sudah disubmit.
        //
        // Definisi "butuh perhatian":
        //   A) Tidak ada submission approved sama sekali dalam 7 hari, ATAU
        //   B) Compliance rate < 50% dari total habit aktif × 7 hari
        //
        // Total "seharusnya" = jumlah habit aktif di sekolah × 7 hari
        // Compliance         = approved submissions / total_expected × 100

        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay()->toDateString();
        $today        = Carbon::now()->toDateString();

        // Semua habit aktif di sekolah guru
        $school           = $user->school;
        $totalActiveHabits = Habit::where('school_id', $school->id)
            ->where('is_active', true)
            ->count();

        // Total submission yang "diharapkan" per siswa dalam 7 hari
        // (setiap habit 1x per hari × 7 hari)
        $expectedPerStudent = max(1, $totalActiveHabits * 7);

        // Semua siswa di kelas guru
        $students = Student::whereIn('g7_kaih_class_id', $myClassIds)
            ->with('user')
            ->get();

        $studentIds = $students->pluck('id');

        // Hitung submission per siswa dalam 7 hari
        // Filter pakai student_id (bukan g7_kaih_class_id) karena submission
        // lama bisa punya g7_kaih_class_id = NULL jika kelas sudah tidak aktif
        $submissionStats = HabitSubmission::whereIn('student_id', $studentIds)
            ->whereBetween('submission_date', [$sevenDaysAgo, $today])
            ->selectRaw("
                student_id,
                COUNT(*) as total,
                SUM(CASE WHEN status = 'teacher_valid' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status IN ('teacher_rejected','parent_rejected') THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN status NOT IN ('teacher_rejected','parent_rejected') THEN 1 ELSE 0 END) as active
            ")
            ->groupBy('student_id')
            ->get()
            ->keyBy('student_id');

        // Perpage untuk pagination attention
        $perPage       = in_array((int) $request->get('attention_per_page', 10), [10, 25, 50, 100])
                         ? (int) $request->get('attention_per_page', 10)
                         : 10;
        $attentionPage = max(1, (int) $request->get('attention_page', 1));

        $allNeedAttention = $students->map(function ($student) use ($submissionStats, $expectedPerStudent) {
            $stat     = $submissionStats->get($student->id);
            $total    = (int) ($stat?->total ?? 0);
            $approved = (int) ($stat?->approved ?? 0);
            $rejected = (int) ($stat?->rejected ?? 0);
            $active   = (int) ($stat?->active ?? 0);

            // PERUBAHAN UTAMA: Compliance dihitung dari semua habit yang SEHARUSNYA dikerjakan
            // bukan dari yang sudah disubmit saja
            // approved / total_habit_aktif_7hari × 100
            $rate = round(($approved / $expectedPerStudent) * 100);

            // Tentukan apakah butuh perhatian
            $noSubmit    = $total === 0;
            $allRejected = $total > 0 && $active === 0;
            // Tambahan: siswa dengan compliance rendah (< 50% dari yang diharapkan)
            $lowCompliance = !$noSubmit && !$allRejected && $rate < 50;

            return [
                'student'          => $student,
                'total'            => $total,
                'approved'         => $approved,
                'rejected'         => $rejected,
                'active'           => $active,
                'rate'             => $rate,
                'expected'         => $expectedPerStudent,
                'no_submit'        => $noSubmit,
                'all_rejected'     => $allRejected,
                'low_compliance'   => $lowCompliance,
                'needs_attention'  => $noSubmit || $allRejected || $lowCompliance,
            ];
        })
        ->filter(fn ($s) => $s['needs_attention'])
        ->sortBy('rate')   // sort dari compliance terendah
        ->values();

        // Manual paginate
        $attentionTotal    = $allNeedAttention->count();
        $attentionSliced   = $allNeedAttention->slice(($attentionPage - 1) * $perPage, $perPage)->values();
        $attentionLastPage = max(1, (int) ceil($attentionTotal / $perPage));

        $validationUrl = route('teacher.validations.index');

        return view('dashboard.teacher', [
            'pendingValidations'       => $pendingValidations,
            'approvedToday'            => $approvedToday,
            'totalStudents'            => $totalStudents,
            'recentPendingSubmissions' => $recentPendingSubmissions,
            'studentsNeedAttention'    => $attentionSliced,
            'attentionTotal'           => $attentionTotal,
            'attentionPage'            => $attentionPage,
            'attentionLastPage'        => $attentionLastPage,
            'attentionPerPage'         => $perPage,
            'totalActiveHabits'        => $totalActiveHabits,
            'expectedPerStudent'       => $expectedPerStudent,
            'validationUrl'            => $validationUrl,
        ]);
    }
}