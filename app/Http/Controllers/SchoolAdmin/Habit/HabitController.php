<?php

namespace App\Http\Controllers\SchoolAdmin\Habit;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Services\G7KAIH\HabitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HabitController extends Controller
{
    use AuthorizesRequests;
    protected $habitService;

    public function __construct(HabitService $habitService)
    {
        $this->habitService = $habitService;
    }

    /**
     * Display a listing of the habits.
     */
    public function index(Request $request)
    {
        $school  = Auth::user()->school;
        $perPage = in_array($request->per_page, [10, 25, 50, 100])
            ? (int) $request->per_page
            : 10;

        $query = Habit::where('school_id', $school->id)
            ->with(['items.rules', 'rules']);

        // Filter: search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter: status (active / inactive)
        if ($request->status !== null && $request->status !== '') {
            $query->where('is_active', (bool) $request->status);
        }

        // Filter: type (applied after eager-loading via collection, or via subquery)
        // We'll handle "multi" and basic filters in DB; "time" filtered post-fetch
        if ($request->type === 'multi') {
            $query->where('is_multi_select', true);
        } elseif ($request->type === 'manual') {
            $query->where('is_multi_select', false);
        }

        $habits = $query->latest()->paginate($perPage)->withQueryString();

        // Post-filter for time-based (requires relationship data)
        if ($request->type === 'time') {
            $allHabits = Habit::where('school_id', $school->id)
                ->with(['items.rules', 'rules'])
                ->where('is_multi_select', false)
                ->latest()
                ->get()
                ->filter(fn($h) =>
                    $h->items->flatMap->rules->where('rule_type', 'time')->count() > 0 ||
                    $h->rules->where('rule_type', 'time')->count() > 0
                );

            // Wrap in a manual paginator
            $currentPage = $request->page ?? 1;
            $items       = $allHabits->forPage($currentPage, $perPage)->values();
            $habits      = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $allHabits->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        }

        // Totals for stat cards (always from full school scope)
        $allSchoolHabits  = Habit::where('school_id', $school->id)->with(['items.rules', 'rules'])->get();
        $totalActive      = $allSchoolHabits->where('is_active', true)->count();
        $totalMultiSelect = $allSchoolHabits->where('is_multi_select', true)->count();
        $totalTimeBased   = $allSchoolHabits->filter(fn($h) =>
            $h->items->flatMap->rules->where('rule_type', 'time')->count() > 0 ||
            $h->rules->where('rule_type', 'time')->count() > 0
        )->count();

        return view('school-admin.habits.index', compact(
            'habits',
            'totalActive',
            'totalMultiSelect',
            'totalTimeBased'
        ));
    }

    /**
     * Show the form for creating a new habit.
     */
    public function create()
    {
        return view('school-admin.habits.create');
    }

    /**
     * Store a newly created habit in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'is_active'        => 'boolean',
            'is_multi_select'  => 'boolean',
            'max_select'       => 'nullable|integer|min:1|max:10',
        ]);

        $validated['is_active']       = $request->has('is_active');
        $validated['is_multi_select'] = $request->has('is_multi_select');

        if (!$validated['is_multi_select']) {
            $validated['max_select'] = null;
        }

        $school = Auth::user()->school;

        try {
            $habit = $this->habitService->createHabit($school, $validated);

            return redirect()
                ->route('school-admin.habits.show', $habit)
                ->with('success', 'Habit berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal membuat habit: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified habit.
     */
    public function show(Habit $habit)
    {
        $this->authorize('view', $habit);
        $habit->load(['items.rules', 'rules', 'school']);

        return view('school-admin.habits.show', compact('habit'));
    }

    /**
     * Show the form for editing the specified habit.
     */
    public function edit(Habit $habit)
    {
        $this->authorize('update', $habit);
        $habit->load(['items']);

        return view('school-admin.habits.edit', compact('habit'));
    }

    /**
     * Update the specified habit in storage.
     */
    public function update(Request $request, Habit $habit)
    {
        $this->authorize('update', $habit);

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'is_active'        => 'boolean',
            'is_multi_select'  => 'boolean',
            'max_select'       => 'nullable|integer|min:1|max:10',
        ]);

        $validated['is_active']       = $request->has('is_active');
        $validated['is_multi_select'] = $request->has('is_multi_select');

        if (!$validated['is_multi_select']) {
            $validated['max_select'] = null;
        }

        try {
            $habit = $this->habitService->updateHabit($habit, $validated);

            return redirect()
                ->route('school-admin.habits.show', $habit)
                ->with('success', 'Habit berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui habit: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified habit from storage.
     */
    public function destroy(Habit $habit)
    {
        $this->authorize('delete', $habit);

        try {
            $this->habitService->deleteHabit($habit);

            return redirect()
                ->route('school-admin.habits.index')
                ->with('success', 'Habit berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus habit: ' . $e->getMessage());
        }
    }

    /**
     * Toggle habit active status.
     */
    public function toggleStatus(Habit $habit)
    {
        $this->authorize('update', $habit);

        try {
            $habit = $this->habitService->toggleHabitStatus($habit);

            return redirect()
                ->route('school-admin.habits.index')
                ->with('success', 'Status habit berhasil diubah menjadi ' . ($habit->is_active ? 'Aktif' : 'Non-Aktif') . '!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah status habit: ' . $e->getMessage());
        }
    }
}