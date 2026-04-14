<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\G7KaihClass;
use App\Models\Habit;
use App\Models\HabitItem;
use App\Models\HabitSubmission;
use App\Models\Student;
use App\Services\G7KAIH\HabitRuleService;
use App\Services\Prayer\PrayerTimeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherPrayerAttendanceController extends Controller
{
    public function __construct(
        protected HabitRuleService  $habitRuleService,
        protected PrayerTimeService $prayerTimeService,
    ) {}

    // ─────────────────────────────────────────────────────────────────
    // INDEX — tampilkan halaman absensi sholat
    // ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $teacher = auth()->user()->teacher;

        abort_unless($teacher, 403, 'Akun bukan guru.');

        $school = auth()->user()->school;

        // ── Cari kelas yang diampu guru
        // PENTING: teacher_id di g7_kaih_classes menyimpan users.id, bukan teachers.id
        $g7kaihClass = G7KaihClass::where('teacher_id', auth()->user()->id)
            ->where('is_active', true)
            ->first();

        // Pilihan waktu sholat
        $prayers = [
            ['key' => 'subuh',   'label' => 'Subuh'],
            ['key' => 'dzuhur',  'label' => 'Dzuhur'],
            ['key' => 'ashar',   'label' => 'Ashar'],
            ['key' => 'maghrib', 'label' => 'Maghrib'],
            ['key' => 'isya',    'label' => 'Isya'],
        ];

        $selectedPrayer = $request->get('prayer', 'dzuhur');

        // ── Cari habit sholat di sekolah ──────────────────────────
        $prayerHabit = Habit::where('school_id', $school->id)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereRaw("LOWER(name) LIKE '%sholat%'")
                  ->orWhereRaw("LOWER(name) LIKE '%solat%'")
                  ->orWhereRaw("LOWER(name) LIKE '%ibadah%'")
                  ->orWhereRaw("LOWER(name) LIKE '%prayer%'");
            })
            ->first();

        $habitId = $prayerHabit?->id;

        // ── Cari habit item yang sesuai waktu sholat terpilih ─────
        $prayerItem = null;
        if ($prayerHabit) {
            $prayerItem = HabitItem::where('habit_id', $prayerHabit->id)
                ->where('is_active', true)
                ->where(function ($q) use ($selectedPrayer) {
                    $q->whereRaw("LOWER(name) LIKE ?", ["%{$selectedPrayer}%"]);

                    $aliases = [
                        'dzuhur'  => 'dhuhr',
                        'dhuhr'   => 'dzuhur',
                        'ashar'   => 'asr',
                        'asr'     => 'ashar',
                        'isya'    => 'isha',
                        'isha'    => 'isya',
                        'subuh'   => 'fajr',
                        'fajr'    => 'subuh',
                    ];
                    if (isset($aliases[$selectedPrayer])) {
                        $q->orWhereRaw("LOWER(name) LIKE ?", ["%{$aliases[$selectedPrayer]}%"]);
                    }
                })
                ->first();
        }

        // ── Waktu sholat hari ini ──────────────────────────────────
        $prayerTimeToday = null;
        $applicableRule  = null;

        try {
            $prayerTimeToday = $this->prayerTimeService->getTodayPrayerTimes($school);

            if ($prayerItem && $prayerTimeToday) {
                $applicableRule = $this->habitRuleService->getApplicableRule(
                    $prayerItem,
                    $school,
                    Carbon::now($school->timezone)
                );

                if (!$applicableRule) {
                    $applicableRule = $this->habitRuleService->getManualRule($prayerItem, $school);
                }

                if (!$applicableRule) {
                    $applicableRule = $prayerItem->rules()
                        ->where('school_id', $school->id)
                        ->orderBy('priority')
                        ->first();
                }
            }
        } catch (\Exception $e) {
            // Lanjut tanpa rule
        }

        // ── Siswa dalam kelas guru ─────────────────────────────────
        $students = collect();
        if ($g7kaihClass) {
            $students = Student::where('g7_kaih_class_id', $g7kaihClass->id)
                ->with('user')
                ->get()
                ->sortBy('user.name');
        }

        // ── Submission hari ini: map[student_id][prayer_key] ──────
        $today = now()->toDateString();

        $todaySubmissions = HabitSubmission::where('submission_date', $today)
            ->whereIn('student_id', $students->pluck('id'))
            ->when($prayerHabit, fn ($q) => $q->where('habit_id', $prayerHabit->id))
            ->with('habitItem')
            ->get();

        $submittedMap   = [];
        $submittedIdMap = [];

        foreach ($todaySubmissions as $sub) {
            $itemName = strtolower($sub->habitItem?->name ?? '');
            foreach ($prayers as $p) {
                if (str_contains($itemName, $p['key'])) {
                    $submittedMap[$sub->student_id][$p['key']]   = true;
                    $submittedIdMap[$sub->student_id][$p['key']] = $sub->id;
                }
            }
        }

        // ── Badge: apakah semua siswa sudah sholat tertentu ───────
        $prayerDoneMap = [];
        foreach ($prayers as $p) {
            $totalStudents = $students->count();
            if ($totalStudents === 0) {
                $prayerDoneMap[$p['key']] = false;
                continue;
            }
            $submittedCount = collect($submittedMap)
                ->filter(fn ($m) => isset($m[$p['key']]) && $m[$p['key']])
                ->count();
            $prayerDoneMap[$p['key']] = $submittedCount >= $totalStudents;
        }

        return view('teacher.prayer-attendance.index', compact(
            'prayers',
            'selectedPrayer',
            'students',
            'submittedMap',
            'submittedIdMap',
            'prayerDoneMap',
            'prayerTimeToday',
            'applicableRule',
            'habitId',
            'prayerItem',
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // STORE — simpan absensi sholat dari guru (bulk)
    // ─────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'prayer'        => 'required|in:subuh,dzuhur,ashar,maghrib,isya',
            'student_ids'   => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'habit_id'      => 'nullable|exists:habits,id',
            'date'          => 'required|date',
        ]);

        $teacher = auth()->user()->teacher;
        abort_unless($teacher, 403);

        $school         = auth()->user()->school;
        $selectedPrayer = $request->prayer;
        $date           = $request->date;

        // ── Cari kelas yang diampu guru ────────────────────────────
        // PENTING: teacher_id di g7_kaih_classes menyimpan users.id, bukan teachers.id
        $g7kaihClass = G7KaihClass::where('teacher_id', auth()->user()->id)
            ->where('is_active', true)
            ->first();

        // ── Cari habit sholat ──────────────────────────────────────
        $prayerHabit = $request->habit_id
            ? Habit::find($request->habit_id)
            : Habit::where('school_id', $school->id)
                ->where(function ($q) {
                    $q->whereRaw("LOWER(name) LIKE '%sholat%'")
                      ->orWhereRaw("LOWER(name) LIKE '%solat%'")
                      ->orWhereRaw("LOWER(name) LIKE '%ibadah%'");
                })
                ->first();

        if (!$prayerHabit) {
            return back()->with('error', 'Habit sholat belum dibuat. Hubungi admin sekolah.');
        }

        // ── Cari habit item sholat yang sesuai ────────────────────
        $prayerItem = HabitItem::where('habit_id', $prayerHabit->id)
            ->where('is_active', true)
            ->where(function ($q) use ($selectedPrayer) {
                $aliases = [
                    'dzuhur' => 'dhuhr',
                    'ashar'  => 'asr',
                    'isya'   => 'isha',
                    'subuh'  => 'fajr',
                ];
                $q->whereRaw("LOWER(name) LIKE ?", ["%{$selectedPrayer}%"]);
                if (isset($aliases[$selectedPrayer])) {
                    $q->orWhereRaw("LOWER(name) LIKE ?", ["%{$aliases[$selectedPrayer]}%"]);
                }
            })
            ->first();

        if (!$prayerItem) {
            return back()->with('error', "Item sholat '{$selectedPrayer}' belum dibuat. Hubungi admin sekolah.");
        }

        // ── Ambil rule yang berlaku ────────────────────────────────
        $now  = Carbon::now($school->timezone);
        $rule = $this->habitRuleService->getApplicableRule($prayerItem, $school, $now)
             ?? $this->habitRuleService->getManualRule($prayerItem, $school)
             ?? $prayerItem->rules()->where('school_id', $school->id)->orderBy('priority')->first();

        $created = 0;
        $skipped = 0;

        DB::transaction(function () use (
            $request, $prayerHabit, $prayerItem, $rule,
            $date, $g7kaihClass, $school, &$created, &$skipped
        ) {
            foreach ($request->student_ids as $studentId) {
                $student = Student::find($studentId);
                if (!$student) continue;

                // Cek sudah submit sholat yang sama hari ini
                $already = HabitSubmission::where('student_id', $student->id)
                    ->where('habit_id',        $prayerHabit->id)
                    ->where('habit_item_id',   $prayerItem->id)
                    ->where('submission_date', $date)
                    ->exists();

                if ($already) {
                    $skipped++;
                    continue;
                }

                // Buat submission
                $g7kaihClassId = $student->g7_kaih_class_id;
                if ($g7kaihClassId && $student->g7kaihClass && !$student->g7kaihClass->is_active) {
                    $g7kaihClassId = null;
                }

                HabitSubmission::create([
                    'student_id'       => $student->id,
                    'g7_kaih_class_id' => $g7kaihClassId,
                    'habit_id'         => $prayerHabit->id,
                    'habit_item_id'    => $prayerItem->id,
                    'habit_rule_id'    => $rule?->id,
                    'submission_date'  => $date,
                    'submitted_at'     => now(),
                    'description'      => 'Dicatat langsung oleh guru',
                    'status'           => 'teacher_valid',
                    'point'            => $rule?->point ?? 0,
                ]);

                $student->recalculateTotalPoint();
                $created++;
            }
        });

        $msg = "Berhasil mencatat {$created} siswa sholat " . ucfirst($request->prayer) . '.';
        if ($skipped > 0) {
            $msg .= " {$skipped} siswa dilewati (sudah tercatat).";
        }

        return redirect()
            ->route('teacher.prayer-attendance.index', ['prayer' => $request->prayer])
            ->with('success', $msg);
    }

    // ─────────────────────────────────────────────────────────────────
    // DESTROY — hapus submission (undo ceklist)
    // ─────────────────────────────────────────────────────────────────
    public function destroy(HabitSubmission $submission)
    {
        $teacher = auth()->user()->teacher;
        abort_unless($teacher, 403);

        abort_unless(
            $submission->status === 'teacher_valid' &&
            $submission->submission_date->isToday(),
            403,
            'Hanya bisa menghapus absensi hari ini.'
        );

        $student = $submission->student;

        DB::transaction(function () use ($submission, $student) {
            $submission->delete();
            $student->recalculateTotalPoint();
        });

        return back()->with('success', 'Absensi berhasil dihapus.');
    }
}