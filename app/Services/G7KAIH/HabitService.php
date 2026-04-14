<?php

namespace App\Services\G7KAIH;

use App\Models\Habit;
use App\Models\School;
use Illuminate\Support\Facades\DB;

class HabitService
{
    /**
     * Create a new habit.
     */
    public function createHabit(School $school, array $data): Habit
    {
        return DB::transaction(function () use ($school, $data) {
            $data['school_id'] = $school->id;
            $data['is_active'] = $data['is_active'] ?? true;

            return Habit::create($data);
        });
    }

    /**
     * Update an existing habit.
     */
    public function updateHabit(Habit $habit, array $data): Habit
    {
        return DB::transaction(function () use ($habit, $data) {
            $habit->update($data);
            return $habit->fresh();
        });
    }

    /**
     * Delete a habit.
     */
    public function deleteHabit(Habit $habit): bool
    {
        return DB::transaction(function () use ($habit) {
            // Cascade delete handled by database foreign key
            return $habit->delete();
        });
    }

    /**
     * Toggle habit active status.
     */
    public function toggleHabitStatus(Habit $habit): Habit
    {
        return DB::transaction(function () use ($habit) {
            $habit->update(['is_active' => !$habit->is_active]);
            return $habit->fresh();
        });
    }

    /**
     * Get habits for a specific school with items and rules.
     */
    public function getSchoolHabitsWithDetails(School $school)
    {
        return Habit::where('school_id', $school->id)
            ->with(['items.rules'])
            ->get();
    }

    /**
     * Check if habit name is prayer/worship habit.
     */
    public function isPrayerHabit(Habit $habit): bool
    {
        $prayerKeywords = ['sholat', 'ibadah', 'solat', 'prayer'];
        $habitName = strtolower($habit->name);

        foreach ($prayerKeywords as $keyword) {
            if (str_contains($habitName, $keyword)) {
                return true;
            }
        }

        return false;
    }
}