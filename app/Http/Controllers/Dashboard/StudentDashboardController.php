<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitSubmission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $student = $user->student;
        $school  = $user->school;

        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $today    = Carbon::now($school?->timezone ?? 'Asia/Jakarta');
        $todayStr = $today->toDateString();

        // ── Submission hari ini ──────────────────────────────────────────
        $todaySubmission = HabitSubmission::where('student_id', $student->id)
            ->where('submission_date', $todayStr)
            ->first();

        // ── Total poin siswa ─────────────────────────────────────────────
        $totalPoint = $student->total_point ?? 0;

        // ── Data 7 hari terakhir untuk grafik ────────────────────────────
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $dateStr = $date->toDateString();

            $submissions = HabitSubmission::where('student_id', $student->id)
                ->where('submission_date', $dateStr)
                ->get();

            $submitted = $submissions->count();
            $validated = $submissions->whereIn('status', ['teacher_valid', 'ai_valid'])->count();
            $validated = $submissions->whereIn('status', ['teacher_valid', 'ai_valid'])->count();
            $points    = $submissions->whereIn('status', ['teacher_valid', 'ai_valid'])->sum('point');

            $weeklyData[] = [
                'date'      => $date->translatedFormat('D'),   // "Sen", "Sel", …
                'full_date' => $date->format('d/m'),
                'submitted' => $submitted,
                'validated' => $validated,
                'points'    => $points,
                'is_today'  => $i === 0,
            ];
        }

        // ── Streak: berapa hari berturut-turut sudah submit ───────────────
        $streak = 0;
        for ($i = 0; $i <= 30; $i++) {
            $dateStr = $today->copy()->subDays($i)->toDateString();
            $exists  = HabitSubmission::where('student_id', $student->id)
                ->where('submission_date', $dateStr)
                ->exists();
            if ($exists) {
                $streak++;
            } else {
                break;
            }
        }

        // ── Habits aktif di sekolah ──────────────────────────────────────
        $activeHabits = collect();
        if ($school) {
            $activeHabits = Habit::where('school_id', $school->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->limit(4)
                ->get();
        }

        // ── Habit items yang "aktif" sekarang (waktu cocok) ──────────────
        // Kita ambil semua submission hari ini untuk cek mana yang sudah submit
        $todaySubmissions = HabitSubmission::where('student_id', $student->id)
            ->where('submission_date', $todayStr)
            ->get()
            ->keyBy(fn($s) => $s->habit_id . '_' . ($s->habit_item_id ?? 'null'));

        // ── Statistik bulan ini ──────────────────────────────────────────
        $monthStart      = $today->copy()->startOfMonth()->toDateString();
        $monthSubmissions = HabitSubmission::where('student_id', $student->id)
            ->whereBetween('submission_date', [$monthStart, $todayStr])
            ->get();

        $monthTotal     = $monthSubmissions->count();
        $monthValidated = $monthSubmissions->whereIn('status', ['teacher_valid', 'ai_valid'])->count();
        $monthPoints    = $monthSubmissions->whereIn('status', ['teacher_valid', 'ai_valid'])->sum('point');

        return view('dashboard.student', compact(
            'todaySubmission',
            'totalPoint',
            'weeklyData',
            'streak',
            'activeHabits',
            'todaySubmissions',
            'monthTotal',
            'monthValidated',
            'monthPoints',
            'today',
            'student',
        ));
    }
}