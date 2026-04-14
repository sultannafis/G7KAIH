<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitSubmission;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $parent = $user->parent;

        abort_unless($parent, 403, 'Profil orang tua tidak ditemukan.');

        $student   = $parent->student;
        $studentId = $parent->student_id;
        $school    = $student->user->school;
        $today     = Carbon::now($school->timezone)->toDateString();

        // ── Jumlah submission pending validasi orang tua ──
        $pendingValidation = HabitSubmission::where('student_id', $studentId)
            ->where('status', 'pending_parent')
            ->count();

        // ── Submission hari ini ──
        $todaySubmissions = HabitSubmission::where('student_id', $studentId)
            ->where('submission_date', $today)
            ->with(['habit', 'habitItem', 'selectedActivities'])
            ->latest('submitted_at')
            ->get()
            ->groupBy(fn($s) => $s->habit_id . '_' . ($s->habit_item_id ?? 'null'));

        // ── Submission pending_parent dari hari SELAIN hari ini ──
        // (dikelompokkan juga by habit_id_itemId, diambil yang terbaru per habit/item)
        $pendingOtherDays = HabitSubmission::where('student_id', $studentId)
            ->where('status', 'pending_parent')
            ->where('submission_date', '!=', $today)
            ->with(['habit', 'habitItem', 'selectedActivities'])
            ->latest('submitted_at')
            ->get()
            ->groupBy(fn($s) => $s->habit_id . '_' . ($s->habit_item_id ?? 'null'));

        // ── Semua habit aktif di sekolah anak ──
        $habits = Habit::where('school_id', $school->id)
            ->where('is_active', true)
            ->with([
                'items' => fn($q) => $q->where('is_active', true)->where('is_activity_option', false),
            ])
            ->latest()
            ->get();

        // ── Bangun habit rows ──
        $habitRows = [];

        foreach ($habits as $habit) {
            $regularItems = $habit->items->where('is_activity_option', false);

            if ($habit->is_multi_select || $regularItems->isEmpty()) {
                $key = $habit->id . '_null';
                // Prioritas: submission hari ini, fallback ke pending dari hari lain
                $submission = $todaySubmissions->get($key)?->first()
                           ?? $pendingOtherDays->get($key)?->first();

                $habitRows[] = [
                    'habit'      => $habit,
                    'item'       => null,
                    'submission' => $submission,
                ];
            } else {
                foreach ($regularItems as $item) {
                    $key = $habit->id . '_' . $item->id;
                    $submission = $todaySubmissions->get($key)?->first()
                               ?? $pendingOtherDays->get($key)?->first();

                    $habitRows[] = [
                        'habit'      => $habit,
                        'item'       => $item,
                        'submission' => $submission,
                    ];
                }
            }
        }

        // Urutan: pending_parent dulu → lainnya yang sudah submit → belum submit
        usort($habitRows, function ($a, $b) {
            $rank = fn($row) => match(true) {
                $row['submission'] === null                      => 2,
                $row['submission']->status === 'pending_parent' => 0,
                default                                         => 1,
            };
            return $rank($a) <=> $rank($b);
        });

        return view('dashboard.parent', compact(
            'pendingValidation',
            'habitRows',
            'today',
        ));
    }
}