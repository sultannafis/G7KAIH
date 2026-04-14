<?php

namespace App\Http\Controllers\SchoolAdmin\Habit;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitItem;
use App\Services\G7KAIH\HabitItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HabitItemController extends Controller
{
    use AuthorizesRequests;
    protected $habitItemService;

    public function __construct(HabitItemService $habitItemService)
    {
        $this->habitItemService = $habitItemService;
    }

    public function index(Request $request)
    {
        $school  = Auth::user()->school;
        $perPage = in_array((int) $request->per_page, [10, 25, 50, 100])
            ? (int) $request->per_page
            : 10;

        $query = HabitItem::whereHas('habit', function ($q) use ($school) {
            $q->where('school_id', $school->id);
        })->with(['habit', 'rules']);

        // Filter habit_id
        if ($request->filled('habit_id')) {
            $query->where('habit_id', $request->habit_id);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('is_active', (bool) $request->status);
        }

        // Filter tipe item
        if ($request->filled('type')) {
            switch ($request->type) {
                case 'activity':
                    $query->where('is_activity_option', true);
                    break;
                case 'single':
                    $query->where('is_activity_option', false)
                          ->where(function ($q) {
                              // bukan prayer: tidak punya meta key terkait waktu sholat
                              $q->whereNull('meta->prayer_field')
                                ->orWhereJsonDoesntContainKey('meta', 'prayer_field');
                          });
                    break;
                case 'prayer':
                    // prayer item ditandai dengan adanya meta prayer_field
                    $query->whereNotNull('meta->prayer_field');
                    break;
            }
        }

        // ─────────────────────────────────────────────────────────────
        // FIX #3: Search — cari berdasarkan nama item atau deskripsi
        // ─────────────────────────────────────────────────────────────
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->latest()->paginate($perPage)->withQueryString();

        // ─────────────────────────────────────────────────────────────
        // FIX #2: eager-load items beserta rules-nya supaya rules count
        // untuk activity option terbaca dengan benar di view
        // ─────────────────────────────────────────────────────────────
        $habits = Habit::where('school_id', $school->id)
            ->where('is_active', true)
            ->withCount('items')
            ->with(['items.rules'])   // <-- pastikan rules ikut di-load
            ->orderBy('name')
            ->get();

        return view('school-admin.habit-items.index', compact('items', 'habits'));
    }

    public function create(Request $request)
    {
        $school             = Auth::user()->school;
        $preselectedHabitId = $request->get('habit_id');
        $habits             = Habit::where('school_id', $school->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('school-admin.habit-items.create', compact('habits', 'preselectedHabitId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'habit_id'           => 'required|exists:habits,id',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'is_active'          => 'boolean',
            'is_activity_option' => 'boolean',
        ]);

        $habit = Habit::findOrFail($validated['habit_id']);
        $this->authorize('update', $habit);

        $validated['is_active']          = $request->has('is_active');
        $validated['is_activity_option'] = $request->has('is_activity_option');

        try {
            $item = $this->habitItemService->createHabitItem($habit, $validated);

            if ($this->habitItemService->isPrayerHabit($habit)) {
                $this->habitItemService->createPrayerRules($item, Auth::user()->school);
            }

            return redirect()
                ->route('school-admin.habit-items.index', ['habit_id' => $habit->id])
                ->with('success', 'Item habit berhasil dibuat!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal membuat item habit: ' . $e->getMessage());
        }
    }

    public function show(HabitItem $item)
    {
        $this->authorize('view', $item->habit);
        $item->load(['rules', 'habit']);

        return view('school-admin.habit-items.show', compact('item'));
    }

    public function edit(HabitItem $item)
    {
        $this->authorize('update', $item->habit);

        $school = Auth::user()->school;
        $habits = Habit::where('school_id', $school->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('school-admin.habit-items.edit', compact('item', 'habits'));
    }

    public function update(Request $request, HabitItem $item)
    {
        $this->authorize('update', $item->habit);

        $validated = $request->validate([
            'habit_id'           => 'required|exists:habits,id',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'is_active'          => 'boolean',
            'is_activity_option' => 'boolean',
        ]);

        $newHabit = Habit::findOrFail($validated['habit_id']);
        $this->authorize('update', $newHabit);

        $validated['is_active']          = $request->has('is_active');
        $validated['is_activity_option'] = $request->has('is_activity_option');

        try {
            $item = $this->habitItemService->updateHabitItem($item, $validated);

            return redirect()
                ->route('school-admin.habit-items.show', $item)
                ->with('success', 'Item habit berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui item habit: ' . $e->getMessage());
        }
    }

    public function destroy(HabitItem $item)
    {
        $this->authorize('delete', $item->habit);

        try {
            $habitId = $item->habit_id;
            $this->habitItemService->deleteHabitItem($item);

            return redirect()
                ->route('school-admin.habit-items.index', ['habit_id' => $habitId])
                ->with('success', 'Item habit berhasil dihapus!');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal menghapus item habit: ' . $e->getMessage());
        }
    }

    public function toggleStatus(HabitItem $item)
    {
        $this->authorize('update', $item->habit);

        try {
            $item = $this->habitItemService->toggleHabitItemStatus($item);

            return response()->json([
                'success'   => true,
                'message'   => 'Status item habit berhasil diubah!',
                'is_active' => $item->is_active,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function syncPrayerTimes(HabitItem $item)
    {
        $this->authorize('update', $item->habit);

        try {
            $school = Auth::user()->school;
            $this->habitItemService->syncPrayerTimesForItem($item, $school);

            return redirect()
                ->route('school-admin.habit-items.show', $item)
                ->with('success', 'Waktu sholat berhasil disinkronkan dan rules telah diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->route('school-admin.habit-items.show', $item)
                ->with('error', 'Gagal menyinkronkan waktu sholat: ' . $e->getMessage());
        }
    }
}