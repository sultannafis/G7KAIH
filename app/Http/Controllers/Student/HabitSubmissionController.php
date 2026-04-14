<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitItem;
use App\Models\HabitRule;
use App\Models\HabitSubmission;
use App\Models\Student;
use App\Services\G7KAIH\HabitRuleService;
use App\Services\G7KAIH\HabitSubmissionService;
use App\Services\G7KAIH\HabitValidationService;
use App\Services\Media\MediaUploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HabitSubmissionController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected HabitSubmissionService $submissionService,
        protected MediaUploadService     $mediaUploadService,
        protected HabitRuleService       $habitRuleService,
        protected HabitValidationService $validationService,
    ) {}

    /**
     * Halaman utama siswa — "Habit Hari Ini"
     * URL: GET /student/habits/today
     */
    public function today(Request $request)
    {
        $user    = Auth::user();
        $school  = $user->school;
        $student = $user->student;

        abort_unless($student, 403, 'Profil siswa tidak ditemukan.');

        $today    = Carbon::now($school->timezone);
        $todayStr = $today->toDateString();
        $nowTime  = $today->format('H:i:s');

        $selectedHabit = null;
        if ($request->filled('habit_id')) {
            $selectedHabit = Habit::where('id', $request->habit_id)
                ->where('school_id', $school->id)
                ->where('is_active', true)
                ->first();
        }

        $habitsQuery = Habit::where('school_id', $school->id)
            ->where('is_active', true)
            ->with([
                'items' => fn($q) => $q->where('is_active', true)->with('rules'),
                'rules',
            ]);

        if ($selectedHabit) {
            $habitsQuery->where('id', $selectedHabit->id);
        }

        $habits = $habitsQuery->get();

        $todaySubmissions = HabitSubmission::where('student_id', $student->id)
            ->where('submission_date', $todayStr)
            ->get()
            ->keyBy(fn($s) => $s->habit_id . '_' . ($s->habit_item_id ?? 'null'));

        $activityCards = [];

        foreach ($habits as $habit) {

            // ── TIPE B: Multi-Select ─────────────────────────────────────
            if ($habit->is_multi_select) {
                $hasActivityOptions  = $habit->items->where('is_activity_option', true)->where('is_active', true)->isNotEmpty();
                $hasMultiSelectRules = $habit->rules->whereNull('habit_item_id')->whereNotNull('min_items_selected')->isNotEmpty();

                if ($hasActivityOptions && $hasMultiSelectRules) {
                    $key        = $habit->id . '_null';
                    $submission = $todaySubmissions->get($key);

                    $activityCards[] = [
                        'type'            => 'action',
                        'habit'           => $habit,
                        'habit_item'      => null,
                        'rules'           => collect(),
                        'submission'      => $submission,
                        'submitted'       => (bool) $submission,
                        'current_rule'    => null,
                        'is_multi_select' => true,
                        'can_submit'      => true,
                        'opens_at'        => null,
                        'sort_key'        => 99999,
                    ];
                    continue;
                }
            }

            // ── TIPE A: Berbasis Item ────────────────────────────────────
            if ($habit->items->isEmpty()) {
                $key        = $habit->id . '_null';
                $submission = $todaySubmissions->get($key);

                $habitRules  = $habit->rules->whereNull('habit_item_id')->sortBy('priority')->values();
                $hasTimeRule = $habitRules->where('rule_type', 'time')->isNotEmpty();

                $currentRule = null;
                $canSubmit   = true;
                $opensAt     = null;
                $sortKey     = 99999;

                if ($hasTimeRule) {
                    $currentRule  = $this->habitRuleService->getApplicableRuleForHabit($habit, $school, $today);
                    $earliestRule = $habitRules->where('rule_type', 'time')->sortBy('start_time')->first();

                    if ($earliestRule?->start_time) {
                        $startTime = $earliestRule->start_time;
                        $endTime   = $habitRules->where('rule_type', 'time')->sortByDesc('end_time')->first()?->end_time;

                        if ($nowTime < $startTime) {
                            $canSubmit = false;
                            $opensAt   = Carbon::createFromFormat('H:i:s', $startTime, $school->timezone)->format('H:i');
                        }

                        $sortKey = $this->timeToSeconds($startTime);

                        if ($endTime && $nowTime > $endTime) {
                            $sortKey += 86400;
                        }
                    }
                } elseif ($habitRules->where('rule_type', 'manual')->isNotEmpty()) {
                    $currentRule = $this->habitRuleService->getManualRuleForHabit($habit, $school);
                }

                $activityCards[] = [
                    'type'            => $hasTimeRule ? 'time' : 'action',
                    'habit'           => $habit,
                    'habit_item'      => null,
                    'rules'           => $habitRules,
                    'submission'      => $submission,
                    'submitted'       => (bool) $submission,
                    'current_rule'    => $currentRule,
                    'is_multi_select' => false,
                    'can_submit'      => $canSubmit,
                    'opens_at'        => $opensAt,
                    'sort_key'        => $sortKey,
                ];

            } else {
                $regularItems = $habit->items->where('is_activity_option', false);
                $itemsToShow  = $regularItems->isNotEmpty() ? $regularItems : $habit->items;

                foreach ($itemsToShow as $item) {
                    $key        = $habit->id . '_' . $item->id;
                    $submission = $todaySubmissions->get($key);

                    $hasTimeRule = $item->rules->where('rule_type', 'time')->isNotEmpty();
                    $itemRules   = $item->rules->sortBy('priority')->values();

                    $currentRule = null;
                    $canSubmit   = true;
                    $opensAt     = null;
                    $sortKey     = 99999;

                    if ($hasTimeRule) {
                        $currentRule  = $this->habitRuleService->getApplicableRule($item, $school, $today);
                        $earliestRule = $itemRules->where('rule_type', 'time')->sortBy('start_time')->first();

                        if ($earliestRule?->start_time) {
                            $startTime = $earliestRule->start_time;
                            $endTime   = $itemRules->where('rule_type', 'time')->sortByDesc('end_time')->first()?->end_time;

                            if ($nowTime < $startTime) {
                                $canSubmit = false;
                                $opensAt   = Carbon::createFromFormat('H:i:s', $startTime, $school->timezone)->format('H:i');
                            }

                            $sortKey = $this->timeToSeconds($startTime);

                            if ($endTime && $nowTime > $endTime) {
                                $sortKey += 86400;
                            }
                        }
                    } else {
                        $currentRule = $this->habitRuleService->getManualRule($item, $school);
                    }

                    $activityCards[] = [
                        'type'            => $hasTimeRule ? 'time' : 'action',
                        'habit'           => $habit,
                        'habit_item'      => $item,
                        'rules'           => $itemRules,
                        'submission'      => $submission,
                        'submitted'       => (bool) $submission,
                        'current_rule'    => $currentRule,
                        'is_multi_select' => false,
                        'can_submit'      => $canSubmit,
                        'opens_at'        => $opensAt,
                        'sort_key'        => $sortKey,
                    ];
                }
            }
        }

        usort($activityCards, function ($a, $b) {
            $aSubmitted = $a['submitted'];
            $bSubmitted = $b['submitted'];
            $aActive    = !is_null($a['current_rule']) && !$aSubmitted;
            $bActive    = !is_null($b['current_rule']) && !$bSubmitted;

            if ($aSubmitted !== $bSubmitted) return $aSubmitted ? 1 : -1;
            if ($aActive !== $bActive)       return $aActive ? -1 : 1;

            return $a['sort_key'] <=> $b['sort_key'];
        });

        return view('student.today', compact('activityCards', 'today', 'student', 'selectedHabit'));
    }

    private function timeToSeconds(string $time): int
    {
        [$h, $m, $s] = explode(':', $time);
        return ((int)$h * 3600) + ((int)$m * 60) + (int)$s;
    }

    /**
     * Form submit habit tertentu.
     * URL: GET /student/habits/{habit}/submit?habit_item_id=
     */
    public function create(Request $request, Habit $habit)
    {
        $user    = Auth::user();
        $school  = $user->school;
        $student = $user->student;

        abort_unless($student, 403);
        abort_unless($habit->school_id === $school->id, 403);

        $today = Carbon::now($school->timezone)->toDateString();

        if ($habit->is_multi_select) {
            $already = HabitSubmission::where('student_id', $student->id)
                ->where('habit_id', $habit->id)
                ->whereNull('habit_item_id')
                ->where('submission_date', $today)
                ->exists();

            if ($already) {
                return redirect(request('from') === 'dashboard' ? route('dashboard.student') : route('student.habits.today'))
                    ->with('info', 'Kamu sudah melakukan kebiasaan ini hari ini!');
            }

            $activityOptions = $habit->items()
                ->where('is_active', true)
                ->where('is_activity_option', true)
                ->orderBy('name')
                ->get();

            $multiSelectRules = $habit->rules()
                ->whereNull('habit_item_id')
                ->whereNotNull('min_items_selected')
                ->orderBy('min_items_selected', 'desc')
                ->get();

            return view('student.create', compact('habit', 'activityOptions', 'multiSelectRules')
                + ['habitItem' => null, 'rules' => collect(), 'currentRule' => null]);
        }

        $habitItem = null;
        if ($request->filled('habit_item_id')) {
            $habitItem = HabitItem::where('id', $request->habit_item_id)
                ->where('habit_id', $habit->id)
                ->firstOrFail();
        }

        $already = HabitSubmission::where('student_id', $student->id)
            ->where('habit_id', $habit->id)
            ->where('habit_item_id', $habitItem?->id)
            ->where('submission_date', $today)
            ->exists();

        if ($already) {
            return redirect(request('from') === 'dashboard' ? route('dashboard.student') : route('student.habits.today'))
                ->with('info', 'Kamu sudah melakukan kebiasaan ini hari ini!');
        }

        $now   = Carbon::now($school->timezone);
        $rules = $habitItem
            ? $habitItem->rules()->orderBy('priority')->get()
            : $habit->rules()->whereNull('habit_item_id')->orderBy('priority')->get();

        $hasTimeRule = $rules->where('rule_type', 'time')->isNotEmpty();

        $currentRule = null;
        if ($hasTimeRule && $habitItem) {
            $currentRule = $this->habitRuleService->getApplicableRule($habitItem, $school, $now);
        } elseif ($hasTimeRule) {
            $currentRule = $this->habitRuleService->getApplicableRuleForHabit($habit, $school, $now);
        } elseif ($habitItem) {
            $currentRule = $this->habitRuleService->getManualRule($habitItem, $school);
        } else {
            $currentRule = $this->habitRuleService->getManualRuleForHabit($habit, $school);
        }

        return view('student.create', compact('habit', 'habitItem', 'rules', 'currentRule')
            + ['activityOptions' => collect(), 'multiSelectRules' => collect()]);
    }

    /**
     * Proses submit habit oleh siswa (dengan foto/deskripsi).
     * URL: POST /student/habits/{habit}/submit
     */
    public function store(Request $request, Habit $habit)
    {
        $user    = Auth::user();
        $school  = $user->school;
        $student = $user->student;

        abort_unless($student, 403);
        abort_unless($habit->school_id === $school->id, 403);

        $now   = Carbon::now($school->timezone);
        $today = $now->toDateString();

        // ── TIPE B: Multi-Select ──────────────────────────────────────────
        if ($habit->is_multi_select) {
            $maxSelect = $habit->max_select ?? 3;

            $validated = $request->validate([
                'selected_activities'   => "required|array|min:1|max:{$maxSelect}",
                'selected_activities.*' => 'required|exists:habit_items,id',
                'description'           => 'required|string|min:10|max:1000',
                'proof'                 => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov|max:20480',
            ]);

            $already = HabitSubmission::where('student_id', $student->id)
                ->where('habit_id', $habit->id)
                ->whereNull('habit_item_id')
                ->where('submission_date', $today)
                ->exists();

            if ($already) {
                return back()->with('error', 'Kamu sudah melakukan kebiasaan ini hari ini!');
            }

            $selectedIds    = $validated['selected_activities'];
            $selectedCount  = count($selectedIds);
            $validItemCount = $habit->items()
                ->whereIn('id', $selectedIds)
                ->where('is_activity_option', true)
                ->count();

            if ($validItemCount !== $selectedCount) {
                return back()->with('error', 'Beberapa aktivitas yang dipilih tidak valid.');
            }

            $applicableRule = $habit->rules()
                ->whereNull('habit_item_id')
                ->where('min_items_selected', '<=', $selectedCount)
                ->orderBy('min_items_selected', 'desc')
                ->orderBy('priority')
                ->first();

            try {
                $mediaFile  = $this->mediaUploadService->upload($request->file('proof'));
                $submission = $this->submissionService->submit(
                    student:     $student,
                    habit:       $habit,
                    habitItem:   null,
                    habitRule:   $applicableRule,
                    submittedAt: $now,
                    mediaFileId: $mediaFile->id,
                    description: $validated['description'],
                );

                foreach ($selectedIds as $itemId) {
                    \DB::table('habit_submission_items')->insert([
                        'habit_submission_id' => $submission->id,
                        'habit_item_id'       => $itemId,
                        'created_at'          => $now,
                        'updated_at'          => $now,
                    ]);
                }

                // ── KIRIM NOTIFIKASI HabitSubmitted ──────────────────────
                $this->submissionService->sendHabitSubmittedNotification($submission);

                $redirectUrl = request('from') === 'dashboard' ? route('dashboard.student') : route('student.habits.today');
                return redirect($redirectUrl)
                    ->with('success', 'Berhasil! Kegiatan kamu sudah dikirim dan menunggu validasi.');

            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Gagal mengirim bukti: ' . $e->getMessage());
            }
        }

        // ── TIPE A: Single Item ───────────────────────────────────────────
        $habitItem = null;
        if ($request->filled('habit_item_id')) {
            $habitItem = HabitItem::where('id', $request->habit_item_id)
                ->where('habit_id', $habit->id)
                ->firstOrFail();
        }

        $isTimeBased = $habitItem
            ? $habitItem->rules()->where('rule_type', 'time')->exists()
            : $habit->rules()->whereNull('habit_item_id')->where('rule_type', 'time')->exists();

        $rules = [
            'proof'       => 'required|file|mimes:jpg,jpeg,png,webp,mp4,mov|max:20480',
            'description' => $isTimeBased ? 'nullable|string|max:500' : 'required|string|max:500',
        ];

        if ($request->filled('habit_item_id')) {
            $rules['habit_item_id'] = 'required|exists:habit_items,id';
        }

        $validated = $request->validate($rules, [
            'description.required' => 'Deskripsi aktivitas wajib diisi untuk kebiasaan ini.',
            'proof.required'       => 'Bukti foto/video wajib diunggah.',
        ]);

        $already = HabitSubmission::where('student_id', $student->id)
            ->where('habit_id', $habit->id)
            ->where('habit_item_id', $habitItem?->id)
            ->where('submission_date', $today)
            ->exists();

        if ($already) {
            return back()->with('error', 'Kamu sudah melakukan kebiasaan ini hari ini!');
        }

        try {
            $mediaFile = $this->mediaUploadService->upload($request->file('proof'));

            $applicableRule = null;
            if ($habitItem) {
                $applicableRule = $isTimeBased
                    ? $this->habitRuleService->getApplicableRule($habitItem, $school, $now)
                    : $this->habitRuleService->getManualRule($habitItem, $school);
            } else {
                $applicableRule = $isTimeBased
                    ? $this->habitRuleService->getApplicableRuleForHabit($habit, $school, $now)
                    : $this->habitRuleService->getManualRuleForHabit($habit, $school);
            }

            $submission = $this->submissionService->submit(
                student:     $student,
                habit:       $habit,
                habitItem:   $habitItem,
                habitRule:   $applicableRule,
                submittedAt: $now,
                mediaFileId: $mediaFile->id,
                description: $validated['description'] ?? null,
            );

            // ── KIRIM NOTIFIKASI HabitSubmitted ──────────────────────────
            $this->submissionService->sendHabitSubmittedNotification($submission);

            $redirectUrl = request('from') === 'dashboard' ? route('dashboard.student') : route('student.habits.today');
            return redirect($redirectUrl)
                ->with('success', 'Berhasil! Bukti kamu sudah dikirim dan menunggu validasi orang tua.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal mengirim bukti: ' . $e->getMessage());
        }
    }

    /**
     * Quick Submit — KHUSUS habit berbasis WAKTU.
     * URL: POST /student/habits/{habit}/quick-submit
     */
    public function quickSubmit(Request $request, Habit $habit)
    {
        $user    = Auth::user();
        $school  = $user->school;
        $student = $user->student;

        abort_unless($student, 403);
        abort_unless($habit->school_id === $school->id, 403);

        $now   = Carbon::now($school->timezone);
        $today = $now->toDateString();

        $habitItem = null;
        if ($request->filled('habit_item_id')) {
            $habitItem = HabitItem::where('id', $request->habit_item_id)
                ->where('habit_id', $habit->id)
                ->firstOrFail();
        }

        $isTimeBased = $habitItem
            ? $habitItem->rules()->where('rule_type', 'time')->exists()
            : $habit->rules()->whereNull('habit_item_id')->where('rule_type', 'time')->exists();

        abort_unless($isTimeBased, 422, 'Quick submit hanya untuk habit berbasis waktu.');

        $already = HabitSubmission::where('student_id', $student->id)
            ->where('habit_id', $habit->id)
            ->where('habit_item_id', $habitItem?->id)
            ->where('submission_date', $today)
            ->exists();

        if ($already) {
            return back()->with('info', 'Kamu sudah mencatat kebiasaan ini hari ini!');
        }

        $applicableRule = $habitItem
            ? $this->habitRuleService->getApplicableRule($habitItem, $school, $now)
            : $this->habitRuleService->getApplicableRuleForHabit($habit, $school, $now);

        try {
            $submission = $this->submissionService->submitWithoutMedia(
                student:     $student,
                habit:       $habit,
                habitItem:   $habitItem,
                habitRule:   $applicableRule,
                submittedAt: $now,
            );

            // ── KIRIM NOTIFIKASI HabitSubmitted ──────────────────────────
            $this->submissionService->sendHabitSubmittedNotification($submission);

            $label    = $habitItem?->name ?? $habit->name;
            $pointMsg = $applicableRule ? ' (+' . $applicableRule->point . ' poin)' : '';

            return back()->with('success', $label . ' berhasil dicatat' . $pointMsg . '. Menunggu validasi orang tua.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencatat: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $user    = Auth::user();
        $student = $user->student;

        abort_unless($student, 403);

        $allowedPerPage = [10, 25, 50, 100];
        $perPage        = in_array((int) $request->input('per_page', 25), $allowedPerPage)
                          ? (int) $request->input('per_page', 25)
                          : 25;

        $query = HabitSubmission::where('student_id', $student->id)
            ->with(['habit', 'habitItem', 'rule', 'validations.validator', 'mediaFiles'])
            ->latest('submitted_at');

        if ($request->filled('date_from')) {
            $query->whereDate('submission_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('submission_date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $submissions = $query->paginate($perPage)->withQueryString();

        return view('student.history', compact('submissions', 'perPage'));
    }

    /**
     * Detail satu submission milik siswa.
     * URL: GET /student/habits/submission/{submission}
     */
    public function show(HabitSubmission $submission)
    {
        $user    = Auth::user();
        $student = $user->student;

        abort_unless($student, 403);
        abort_unless($submission->student_id === $student->id, 403);

        $submission->load(['habit', 'habitItem', 'rule', 'validations.validator', 'mediaFiles']);

        return view('student.show', compact('submission'));
    }
}