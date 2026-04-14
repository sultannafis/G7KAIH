<?php

namespace App\Http\Controllers\SchoolAdmin\Habit;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitItem;
use App\Models\HabitRule;
use App\Services\G7KAIH\HabitRuleService;
use App\Services\Prayer\PrayerTimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HabitRuleController extends Controller
{
    use AuthorizesRequests;
    protected $habitRuleService;
    protected $prayerTimeService;

    public function __construct(
        HabitRuleService $habitRuleService,
        PrayerTimeService $prayerTimeService
    ) {
        $this->habitRuleService  = $habitRuleService;
        $this->prayerTimeService = $prayerTimeService;
    }

    public function index(Request $request)
{
    $school  = Auth::user()->school;
    $perPage = in_array($request->per_page, [10, 25, 50, 100])
        ? (int) $request->per_page
        : 15; // ← ganti dari hardcode paginate(15)

    $query = HabitRule::with(['habitItem.habit', 'habit', 'school']) // tambah 'habit'
        ->where(function ($q) use ($school) {
            $q->whereNull('school_id')
              ->orWhere('school_id', $school->id);
        });

    if ($request->filled('habit_id')) {
        $query->whereHas('habitItem', function ($q) use ($request) {
            $q->where('habit_id', $request->habit_id);
        });
    }

    if ($request->filled('habit_item_id')) {
        $query->where('habit_item_id', $request->habit_item_id);
    }

    $rules = $query->orderBy('priority')->paginate($perPage)->withQueryString();

    $habits     = Habit::where('school_id', $school->id)->get();
    $habitItems = HabitItem::when($request->filled('habit_id'), function ($q) use ($request) {
        return $q->where('habit_id', $request->habit_id);
    })->get();

    return view('school-admin.habit-rules.index', compact('rules', 'habits', 'habitItems'));
}

    public function create(Request $request)
    {
        $school      = Auth::user()->school;
        $habits      = Habit::where('school_id', $school->id)->with('items')->get();
        $habitId     = $request->get('habit_id');
        $habitItemId = $request->get('habit_item_id');

        return view('school-admin.habit-rules.create', compact('habits', 'habitId', 'habitItemId'));
    }

    public function store(Request $request)
    {
        $school = Auth::user()->school;

        $validated = $request->validate([
            'habit_id'                  => 'required|exists:habits,id',
            'habit_item_id'             => 'nullable|exists:habit_items,id',
            'name'                      => 'required|string|max:255',
            'rule_type'                 => 'required|in:time,manual',
            'start_time'                => 'nullable|required_if:rule_type,time|date_format:H:i',
            'end_time'                  => 'nullable|required_if:rule_type,time|date_format:H:i|after:start_time',
            'min_items_selected'        => 'nullable|integer|min:1',
            'point'                     => 'required|integer|min:0',
            'priority'                  => 'required|integer|min:1',
            'require_parent_validation' => 'boolean',
            'allow_ai_validation'       => 'boolean',
        ]);

        if (!empty($validated['habit_item_id'])) {
            $habitItem = HabitItem::find($validated['habit_item_id']);
            if ($habitItem->habit_id != $validated['habit_id']) {
                return back()->withInput()->with('error', 'Item habit tidak sesuai dengan habit yang dipilih!');
            }
        } else {
            $validated['habit_item_id'] = null;
        }

        // Jika rule_type bukan manual, hapus min_items_selected
        if ($validated['rule_type'] !== 'manual') {
            $validated['min_items_selected'] = null;
        }

        $validated['require_parent_validation'] = $request->has('require_parent_validation');
        $validated['allow_ai_validation']       = $request->has('allow_ai_validation');

        try {
            $this->habitRuleService->createRule($school, $validated);

            return redirect()
                ->route('school-admin.habit-rules.index')
                ->with('success', 'Rule habit berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal membuat rule habit: ' . $e->getMessage());
        }
    }

    public function show(HabitRule $rule)
    {
        $this->authorize('view', $rule);
        $rule->load(['habitItem.habit', 'school']);

        return view('school-admin.habit-rules.show', compact('rule'));
    }

    public function edit(HabitRule $rule)
    {
        $this->authorize('update', $rule);
        $rule->load(['habitItem.habit']);

        $school = Auth::user()->school;
        $habits = Habit::where('school_id', $school->id)->with('items')->get();

        if (empty($rule->habit_id) && $rule->habitItem) {
            $rule->habit_id = $rule->habitItem->habit_id;
        }

        return view('school-admin.habit-rules.edit', compact('rule', 'habits'));
    }

    public function update(Request $request, HabitRule $rule)
    {
        $this->authorize('update', $rule);

        $validated = $request->validate([
            'habit_id'                  => 'required|exists:habits,id',
            'habit_item_id'             => 'nullable|exists:habit_items,id',
            'name'                      => 'required|string|max:255',
            'rule_type'                 => 'required|in:time,manual',
            'start_time'                => 'nullable|required_if:rule_type,time|date_format:H:i',
            'end_time'                  => 'nullable|required_if:rule_type,time|date_format:H:i|after:start_time',
            'min_items_selected'        => 'nullable|integer|min:1',
            'point'                     => 'required|integer|min:0',
            'priority'                  => 'required|integer|min:1',
            'require_parent_validation' => 'boolean',
            'allow_ai_validation'       => 'boolean',
        ]);

        if (!empty($validated['habit_item_id'])) {
            $habitItem = HabitItem::find($validated['habit_item_id']);
            if ($habitItem->habit_id != $validated['habit_id']) {
                return back()->withInput()->with('error', 'Item habit tidak sesuai dengan habit yang dipilih!');
            }
        } else {
            $validated['habit_item_id'] = null;
        }

        // Jika rule_type bukan manual, hapus min_items_selected
        if ($validated['rule_type'] !== 'manual') {
            $validated['min_items_selected'] = null;
        }

        $validated['require_parent_validation'] = $request->has('require_parent_validation');
        $validated['allow_ai_validation']       = $request->has('allow_ai_validation');

        try {
            $this->habitRuleService->updateRule($rule, $validated);

            return redirect()
                ->route('school-admin.habit-rules.index')
                ->with('success', 'Rule habit berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui rule habit: ' . $e->getMessage());
        }
    }

    public function destroy(HabitRule $rule)
    {
        $this->authorize('delete', $rule);

        try {
            $this->habitRuleService->deleteRule($rule);

            return redirect()
                ->route('school-admin.habit-rules.index')
                ->with('success', 'Rule habit berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus rule habit: ' . $e->getMessage());
        }
    }

    /**
     * ✅ DIPERBAIKI: Generate prayer rules untuk satu item → redirect + flash message.
     */
    public function generatePrayerRules(HabitItem $habitItem)
    {
        $this->authorize('update', $habitItem->habit);

        $school    = Auth::user()->school;
        $habitName = strtolower($habitItem->habit->name);

        if (!str_contains($habitName, 'sholat') && !str_contains($habitName, 'ibadah')) {
            return redirect()
                ->route('school-admin.habit-items.show', $habitItem)
                ->with('error', 'Fitur ini hanya untuk habit Sholat/Ibadah!');
        }

        try {
            $today      = Carbon::now($school->timezone)->format('d-m-Y');
            $prayerTime = $this->prayerTimeService->sync($school, $today);
            $rules      = $this->habitRuleService->generatePrayerRulesForItem($habitItem, $school, $prayerTime);

            return redirect()
                ->route('school-admin.habit-items.show', $habitItem)
                ->with('success', 'Rules waktu sholat berhasil di-generate! (' . count($rules) . ' rules dibuat)');
        } catch (\Exception $e) {
            return redirect()
                ->route('school-admin.habit-items.show', $habitItem)
                ->with('error', 'Gagal generate rules: ' . $e->getMessage());
        }
    }

    /**
     * ✅ DIPERBAIKI: Bulk generate prayer rules untuk semua item di satu habit → redirect + flash message.
     */
    public function bulkGeneratePrayerRules(Habit $habit)
    {
        $this->authorize('update', $habit);

        $school    = Auth::user()->school;
        $habitName = strtolower($habit->name);

        if (!str_contains($habitName, 'sholat') && !str_contains($habitName, 'ibadah')) {
            return redirect()
                ->route('school-admin.habits.show', $habit)
                ->with('error', 'Fitur ini hanya untuk habit Sholat/Ibadah!');
        }

        try {
            $today      = Carbon::now($school->timezone)->format('d-m-Y');
            $prayerTime = $this->prayerTimeService->sync($school, $today);
            $allRules   = $this->habitRuleService->bulkGeneratePrayerRules($habit, $school, $prayerTime);

            return redirect()
                ->route('school-admin.habits.show', $habit)
                ->with('success', 'Semua rules waktu sholat berhasil di-generate! (' . count($allRules) . ' rules dibuat)');
        } catch (\Exception $e) {
            return redirect()
                ->route('school-admin.habits.show', $habit)
                ->with('error', 'Gagal generate rules: ' . $e->getMessage());
        }
    }

    /**
     * ✅ DIPERBAIKI: Preview waktu sholat → render view (bukan JSON).
     * GET /school-admin/habit-rules/preview-prayer-times?date=2026-02-26
     */
    public function previewPrayerTimes(Request $request)
    {
        $school = Auth::user()->school;

        // Default ke hari ini jika tidak ada parameter date
        $dateInput = $request->get('date', Carbon::now($school->timezone)->format('Y-m-d'));

        $validated = validator(['date' => $dateInput], [
            'date' => 'required|date_format:Y-m-d',
        ])->validate();

        $prayerTime    = null;
        $prayerSchedule = null;
        $error          = null;

        try {
            $date           = Carbon::parse($validated['date'])->format('d-m-Y');
            $prayerTime     = $this->prayerTimeService->sync($school, $date);
            $prayerSchedule = $this->habitRuleService->getPrayerScheduleWithCategories($prayerTime);
        } catch (\Exception $e) {
            $error = 'Gagal mengambil waktu sholat: ' . $e->getMessage();
        }

        return view('school-admin.habit-rules.preview-prayer-times', compact(
            'prayerTime',
            'prayerSchedule',
            'error',
            'school'
        ));
    }

    /**
     * Update priorities — tetap JSON karena dipakai drag-and-drop reorder.
     */
    public function updatePriorities(Request $request)
    {
        $validated = $request->validate([
            'rules'            => 'required|array',
            'rules.*.id'       => 'required|exists:habit_rules,id',
            'rules.*.priority' => 'required|integer|min:1',
        ]);

        try {
            foreach ($validated['rules'] as $ruleData) {
                $rule = HabitRule::find($ruleData['id']);
                $this->authorize('update', $rule);
                $rule->update(['priority' => $ruleData['priority']]);
            }

            return response()->json(['success' => true, 'message' => 'Prioritas rules berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui prioritas: ' . $e->getMessage()], 500);
        }
    }
}