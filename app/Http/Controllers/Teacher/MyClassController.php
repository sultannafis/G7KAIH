<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\G7KaihClass;
use App\Models\Habit;
use App\Models\HabitSubmission;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class MyClassController extends Controller
{
    public function index(Request $request)
    {
        $user   = Auth::user();
        $school = $user->school;

        $myClass = G7KaihClass::where('teacher_id', $user->id)
            ->where('school_id', $school->id)
            ->where('is_active', true)
            ->with(['students.user', 'students.parents.user'])
            ->first();

        if (!$myClass) {
            return view('teacher.my-class.index', [
                'myClass'         => null,
                'students'        => collect(),
                'habits'          => collect(),
                'today'           => Carbon::now($school->timezone),
                'search'          => '',
                'studentStats'    => [],
                'totalDailySlots' => 0,
            ]);
        }

        $today    = Carbon::now($school->timezone);
        $todayStr = $today->toDateString();
        $search   = $request->get('search', '');

        $habits = Habit::where('school_id', $school->id)
            ->where('is_active', true)
            ->with(['items' => fn($q) => $q->where('is_active', true)->where('is_activity_option', false)])
            ->get();

        $totalDailySlots = $habits->sum(function ($habit) {
            if ($habit->is_multi_select) return 1;
            $regularItems = $habit->items->where('is_activity_option', false);
            return $regularItems->count() > 0 ? $regularItems->count() : 1;
        });

        // Search by nama, NIS, atau NISN
        $studentsQuery = $myClass->students()->with(['user', 'parents.user']);
        if ($search) {
            $studentsQuery->where(function ($q) use ($search) {
                $q->whereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"))
                  ->orWhere('nis',  'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }
        $students = $studentsQuery->get();

        $todaySubmissions = HabitSubmission::where('g7_kaih_class_id', $myClass->id)
            ->where('submission_date', $todayStr)
            ->whereIn('status', ['pending_parent', 'pending_ai', 'ai_valid', 'ai_rejected', 'teacher_valid'])
            ->get()
            ->groupBy('student_id');

        $studentStats = [];
        foreach ($students as $student) {
            $subs           = $todaySubmissions->get($student->id, collect());
            $submittedSlots = $subs->count();
            $todayPoints    = $subs->where('status', 'teacher_valid')->sum('point');
            $completionRate = $totalDailySlots > 0
                ? min(100, round($submittedSlots / $totalDailySlots * 100))
                : 0;

            $dailyStatus = match (true) {
                $completionRate >= 100 => 'sangat_baik',
                $completionRate >= 70  => 'baik',
                $completionRate >= 40  => 'cukup',
                $completionRate > 0    => 'kurang',
                default                => 'belum',
            };

            $parentUser = $student->parents->first()?->user;

            $studentStats[$student->id] = [
                'student'         => $student,
                'submitted_slots' => $submittedSlots,
                'total_slots'     => $totalDailySlots,
                'completion_rate' => $completionRate,
                'daily_status'    => $dailyStatus,
                'today_points'    => $todayPoints,
                'total_points'    => $student->total_point ?? 0,
                'parent_name'     => $parentUser?->name ?? '-',
                'subs_today'      => $subs,
            ];
        }

        $statusOrder  = ['sangat_baik' => 0, 'baik' => 1, 'cukup' => 2, 'kurang' => 3, 'belum' => 4];
        $studentStats = collect($studentStats)
            ->sortBy(fn($s) => $statusOrder[$s['daily_status']])
            ->values()
            ->keyBy(fn($s) => $s['student']->id)
            ->all();

        return view('teacher.my-class.index', compact(
            'myClass', 'students', 'habits', 'today',
            'search', 'studentStats', 'totalDailySlots',
        ));
    }

    /**
     * Tampilkan laporan di browser (bisa print manual).
     * GET /teacher/my-class/student/{student}/report
     */
    public function studentReport(Request $request, Student $student)
    {
        $data = $this->buildReportData($request, $student);

        return view('teacher.my-class.student-report', $data);
    }

    /**
     * Download laporan sebagai file PDF langsung — pakai DomPDF.
     * GET /teacher/my-class/student/{student}/report/download
     */
    public function studentReportDownload(Request $request, Student $student)
    {
        $data = $this->buildReportData($request, $student);

        $pdf = Pdf::loadView('teacher.my-class.student-report-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont'          => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'dpi'                  => 150,
            ]);

        $name     = $data['student']->user->name;
        $from     = $data['dateFrom']->format('Ymd');
        $to       = $data['dateTo']->format('Ymd');
        $filename = 'laporan-' . str($name)->slug() . '-' . $from . '-' . $to . '.pdf';

        return $pdf->download($filename);
    }

    // ── shared builder ──────────────────────────────────────────────
    private function buildReportData(Request $request, Student $student): array
    {
        $user   = Auth::user();
        $school = $user->school;

        $myClass = G7KaihClass::where('teacher_id', $user->id)
            ->where('school_id', $school->id)
            ->where('is_active', true)
            ->first();

        abort_unless($myClass, 403, 'Kamu tidak memiliki kelas aktif.');
        abort_unless($student->g7_kaih_class_id === $myClass->id, 403, 'Siswa bukan bagian dari kelasmu.');

        $student->load(['user', 'g7kaihClass.teacher', 'parents.user']);

        $dateFrom = $request->get('date_from')
            ? Carbon::parse($request->get('date_from'))->startOfDay()
            : Carbon::now($school->timezone)->startOfMonth();

        $dateTo = $request->get('date_to')
            ? Carbon::parse($request->get('date_to'))->endOfDay()
            : Carbon::now($school->timezone)->endOfDay();

        $habits = Habit::where('school_id', $school->id)
            ->where('is_active', true)
            ->with([
                'items' => fn($q) => $q->where('is_active', true)->where('is_activity_option', false),
                'rules',
            ])
            ->get();

        $submissions = HabitSubmission::where('student_id', $student->id)
            ->whereBetween('submission_date', [$dateFrom->toDateString(), $dateTo->toDateString()])
            ->with(['habit', 'habitItem', 'rule'])
            ->get();

        $periodDays = (int) $dateFrom->diffInDays($dateTo) + 1;
        $dailyData  = [];

        for ($i = 0; $i < $periodDays; $i++) {
            $date    = $dateFrom->copy()->addDays($i)->toDateString();
            $daySubs = $submissions->filter(fn($s) => $s->submission_date->toDateString() === $date)->values();

            $dailyData[$date] = [
                'date'        => $date,
                'submissions' => $daySubs,
                'by_habit'    => $daySubs->groupBy('habit_id'),
            ];
        }

        $habitRecap = [];
        foreach ($habits as $habit) {
            $regularItems    = $habit->items->where('is_activity_option', false);
            $slots           = $habit->is_multi_select ? 1 : ($regularItems->count() > 0 ? $regularItems->count() : 1);
            $habitSubs       = $submissions->where('habit_id', $habit->id);
            $activeDays      = $habitSubs->groupBy(fn($s) => $s->submission_date->toDateString())->count();
            $earnedPoints    = $habitSubs->where('status', 'teacher_valid')->sum('point');
            $allRulePoints   = $habit->items->flatMap->rules->pluck('point')->merge($habit->rules->pluck('point'));
            $maxPointPerSlot = $allRulePoints->max() ?? 0;
            $maxPoints       = $maxPointPerSlot * $slots * $periodDays;
            $pct             = $maxPoints > 0 ? min(100, round($earnedPoints / $maxPoints * 100)) : 0;
            $predikat        = $this->getPredikat($habit->name, $activeDays, $pct);

            $habitRecap[] = compact('habit', 'activeDays', 'earnedPoints', 'maxPoints', 'pct', 'predikat', 'slots');
        }

        $totalEarned     = collect($habitRecap)->sum('earnedPoints');
        $totalMax        = collect($habitRecap)->sum('maxPoints');
        $totalPct        = $totalMax > 0 ? min(100, round($totalEarned / $totalMax * 100)) : 0;
        $overallPredikat = match (true) {
            $totalPct >= 80 => 'Sangat Baik',
            $totalPct >= 60 => 'Baik',
            $totalPct >= 40 => 'Cukup',
            default         => 'Perlu Perbaikan',
        };

        return compact(
            'student', 'myClass', 'school', 'habits', 'habitRecap',
            'dailyData', 'dateFrom', 'dateTo', 'periodDays',
            'totalEarned', 'totalMax', 'totalPct', 'overallPredikat',
        );
    }

    protected function getPredikat(string $habitName, int $activeDays, int $pct): string
    {
        if ($activeDays == 0 && $pct == 0) return '-';
        if ($pct >= 70) return 'Sudah Terbiasa';
        if ($pct >= 36) return 'Terbiasa';
        return 'Belum Terbiasa';
    }
}