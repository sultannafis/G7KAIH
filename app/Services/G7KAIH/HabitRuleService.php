<?php

namespace App\Services\G7KAIH;

use App\Models\Habit;
use App\Models\HabitItem;
use App\Models\HabitRule;
use App\Models\PrayerTime;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HabitRuleService
{
    /**
     * Create a new habit rule.
     */
    public function createRule(School $school, array $data): HabitRule
    {
        return DB::transaction(function () use ($school, $data) {
            $data['school_id'] = $school->id;

            // Set default values
            $data['require_parent_validation'] = $data['require_parent_validation'] ?? true;
            $data['allow_ai_validation'] = $data['allow_ai_validation'] ?? true;

            // Jika rule_type manual, kosongkan start_time dan end_time
            if ($data['rule_type'] === 'manual') {
                $data['start_time'] = null;
                $data['end_time'] = null;
            }

            return HabitRule::create($data);
        });
    }

    /**
     * Update an existing habit rule.
     */
    public function updateRule(HabitRule $rule, array $data): HabitRule
    {
        return DB::transaction(function () use ($rule, $data) {
            // Jika rule_type manual, kosongkan start_time dan end_time
            if ($data['rule_type'] === 'manual') {
                $data['start_time'] = null;
                $data['end_time'] = null;
            }

            $rule->update($data);
            return $rule->fresh();
        });
    }

    /**
     * Delete a habit rule.
     */
    public function deleteRule(HabitRule $rule): bool
    {
        return DB::transaction(function () use ($rule) {
            return $rule->delete();
        });
    }

    /**
     * Generate prayer rules for a specific habit item based on API prayer times.
     *
     * System:
     * - Awal Waktu  : waktu sholat s/d +30 menit = 100 point
     * - Tengah Waktu: +31 s/d +60 menit           =  75 point
     * - Akhir Waktu : setelah +60 menit            =  50 point
     */
    public function generatePrayerRulesForItem(
        HabitItem $item,
        School $school,
        PrayerTime $prayerTime
    ): array {
        return DB::transaction(function () use ($item, $school, $prayerTime) {
            // Pastikan item punya habit_id
            if (!$item->habit_id) {
                throw new \Exception("Item tidak memiliki habit_id yang valid.");
            }

            // Mapping nama sholat Indonesia ke field di PrayerTime
            $prayerMapping = [
                'subuh'   => 'subuh',
                'fajr'    => 'subuh',
                'dzuhur'  => 'dzuhur',
                'dhuhr'   => 'dzuhur',
                'ashar'   => 'ashar',
                'asr'     => 'ashar',
                'maghrib' => 'maghrib',
                'isya'    => 'isya',
                'isha'    => 'isya',
            ];

            $itemName   = strtolower($item->name);
            $prayerField = null;

            foreach ($prayerMapping as $key => $field) {
                if (str_contains($itemName, $key)) {
                    $prayerField = $field;
                    break;
                }
            }

            if (!$prayerField) {
                throw new \Exception("Item '{$item->name}' tidak dikenali sebagai waktu sholat.");
            }

            // Ambil waktu sholat dari prayer time
            $prayerTimeValue = $prayerTime->$prayerField;

            if (!$prayerTimeValue) {
                throw new \Exception("Waktu sholat {$prayerField} tidak ditemukan.");
            }

            Log::info("Processing prayer time for item", [
                'item_id'           => $item->id,
                'item_name'         => $item->name,
                'prayer_field'      => $prayerField,
                'prayer_time_value' => $prayerTimeValue,
            ]);

            // Parse waktu sholat - handle format H:i:s atau H:i
            try {
                $timeValue = trim($prayerTimeValue);

                // Jika format H:i (5 karakter), tambahkan :00
                if (strlen($timeValue) === 5 && substr_count($timeValue, ':') === 1) {
                    $timeValue .= ':00';
                }

                $baseTime = Carbon::createFromFormat('H:i:s', $timeValue, $school->timezone);
            } catch (\Exception $e) {
                Log::error("Failed to parse prayer time", [
                    'prayer_time_value' => $prayerTimeValue,
                    'error'             => $e->getMessage(),
                ]);
                throw new \Exception("Format waktu sholat tidak valid: {$prayerTimeValue}");
            }

            // Hapus rules lama untuk item ini
            HabitRule::where('habit_item_id', $item->id)
                ->where('school_id', $school->id)
                ->delete();

            $rules = [];

            // Rule 1: Awal Waktu (0–30 menit setelah azan) - 100 point
            $rules[] = HabitRule::create([
                'habit_id'                  => $item->habit_id,
                'habit_item_id'             => $item->id,
                'school_id'                 => $school->id,
                'name'                      => 'Awal Waktu',
                'rule_type'                 => 'time',
                'start_time'                => $baseTime->format('H:i:s'),
                'end_time'                  => $baseTime->copy()->addMinutes(30)->format('H:i:s'),
                'point'                     => 100,
                'priority'                  => 1,
                'require_parent_validation' => true,
                'allow_ai_validation'       => true,
            ]);

            // Rule 2: Tengah Waktu (31–60 menit setelah azan) - 75 point
            $rules[] = HabitRule::create([
                'habit_id'                  => $item->habit_id,
                'habit_item_id'             => $item->id,
                'school_id'                 => $school->id,
                'name'                      => 'Tengah Waktu',
                'rule_type'                 => 'time',
                'start_time'                => $baseTime->copy()->addMinutes(30)->addSecond()->format('H:i:s'),
                'end_time'                  => $baseTime->copy()->addMinutes(60)->format('H:i:s'),
                'point'                     => 75,
                'priority'                  => 2,
                'require_parent_validation' => true,
                'allow_ai_validation'       => true,
            ]);

            // Rule 3: Akhir Waktu (61+ menit setelah azan) - 50 point
            $endOfDayOrNextPrayer = $this->getEndTimeForLatePrayer($prayerField, $prayerTime, $school);

            $rules[] = HabitRule::create([
                'habit_id'                  => $item->habit_id,
                'habit_item_id'             => $item->id,
                'school_id'                 => $school->id,
                'name'                      => 'Akhir Waktu',
                'rule_type'                 => 'time',
                'start_time'                => $baseTime->copy()->addMinutes(60)->addSecond()->format('H:i:s'),
                'end_time'                  => $endOfDayOrNextPrayer,
                'point'                     => 50,
                'priority'                  => 3,
                'require_parent_validation' => true,
                'allow_ai_validation'       => true,
            ]);

            Log::info("Successfully generated prayer rules", [
                'item_id'     => $item->id,
                'rules_count' => count($rules),
            ]);

            return $rules;
        });
    }

    /**
     * Get end time for late prayer (before next prayer or end of day).
     */
    protected function getEndTimeForLatePrayer(
        string $currentPrayer,
        PrayerTime $prayerTime,
        School $school
    ): string {
        $prayerOrder  = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
        $currentIndex = array_search($currentPrayer, $prayerOrder);

        // Jika ada sholat berikutnya, gunakan waktu sholat berikutnya
        if ($currentIndex !== false && $currentIndex < count($prayerOrder) - 1) {
            $nextPrayer     = $prayerOrder[$currentIndex + 1];
            $nextPrayerTime = $prayerTime->$nextPrayer;

            if ($nextPrayerTime) {
                try {
                    $timeValue = trim($nextPrayerTime);
                    if (strlen($timeValue) === 5 && substr_count($timeValue, ':') === 1) {
                        $timeValue .= ':00';
                    }

                    return Carbon::createFromFormat('H:i:s', $timeValue, $school->timezone)
                        ->subMinute()
                        ->format('H:i:s');
                } catch (\Exception $e) {
                    Log::warning("Failed to parse next prayer time", [
                        'next_prayer'      => $nextPrayer,
                        'next_prayer_time' => $nextPrayerTime,
                        'error'            => $e->getMessage(),
                    ]);
                }
            }
        }

        // Jika Isya atau tidak ada next prayer, gunakan end of day
        return '23:59:59';
    }

    /**
     * Bulk generate prayer rules for all prayer items in a habit.
     */
    public function bulkGeneratePrayerRules(
        Habit $habit,
        School $school,
        PrayerTime $prayerTime
    ): array {
        return DB::transaction(function () use ($habit, $school, $prayerTime) {
            $allRules = [];

            $items = HabitItem::where('habit_id', $habit->id)
                ->where('is_active', true)
                ->get();

            foreach ($items as $item) {
                try {
                    $rules    = $this->generatePrayerRulesForItem($item, $school, $prayerTime);
                    $allRules = array_merge($allRules, $rules);
                } catch (\Exception $e) {
                    Log::warning("Failed to generate rules for item {$item->id}: " . $e->getMessage());
                }
            }

            return $allRules;
        });
    }

    /**
     * Update existing prayer rules for an item based on new prayer times.
     */
    public function updatePrayerRulesForItem(
        HabitItem $item,
        School $school,
        PrayerTime $prayerTime
    ): array {
        // Simply regenerate the rules
        return $this->generatePrayerRulesForItem($item, $school, $prayerTime);
    }

    /**
     * Get prayer schedule with time categories for display.
     */
    public function getPrayerScheduleWithCategories(PrayerTime $prayerTime): array
    {
        $schedule = [];
        $prayers  = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];

        foreach ($prayers as $prayer) {
            $time = $prayerTime->$prayer;
            if (!$time) continue;

            try {
                $timeValue = trim($time);
                if (strlen($timeValue) === 5 && substr_count($timeValue, ':') === 1) {
                    $timeValue .= ':00';
                }

                $baseTime = Carbon::createFromFormat('H:i:s', $timeValue);

                $schedule[$prayer] = [
                    'name'        => ucfirst($prayer),
                    'prayer_time' => $time,
                    'categories'  => [
                        [
                            'name'  => 'Awal Waktu',
                            'start' => $baseTime->format('H:i'),
                            'end'   => $baseTime->copy()->addMinutes(30)->format('H:i'),
                            'point' => 100,
                        ],
                        [
                            'name'  => 'Tengah Waktu',
                            'start' => $baseTime->copy()->addMinutes(30)->addSecond()->format('H:i'),
                            'end'   => $baseTime->copy()->addMinutes(60)->format('H:i'),
                            'point' => 75,
                        ],
                        [
                            'name'  => 'Akhir Waktu',
                            'start' => $baseTime->copy()->addMinutes(60)->addSecond()->format('H:i'),
                            'point' => 50,
                        ],
                    ],
                ];
            } catch (\Exception $e) {
                Log::warning("Failed to process prayer time for schedule", [
                    'prayer' => $prayer,
                    'time'   => $time,
                    'error'  => $e->getMessage(),
                ]);
            }
        }

        return $schedule;
    }

    /**
     * Get applicable rule for a submission based on time.
     */
    public function getApplicableRule(
        HabitItem $item,
        School $school,
        Carbon $submittedAt
    ): ?HabitRule {
        $time = $submittedAt->format('H:i:s');

        return HabitRule::where('habit_item_id', $item->id)
            ->where(function ($q) use ($school) {
                $q->whereNull('school_id')
                  ->orWhere('school_id', $school->id);
            })
            ->where('rule_type', 'time')
            ->where('start_time', '<=', $time)
            ->where('end_time', '>=', $time)
            ->orderBy('priority', 'asc')
            ->first();
    }

    /**
     * Get rule for manual (non-time-based) habits.
     */
    public function getManualRule(HabitItem $item, School $school): ?HabitRule
    {
        return HabitRule::where('habit_item_id', $item->id)
            ->where(function ($q) use ($school) {
                $q->whereNull('school_id')
                  ->orWhere('school_id', $school->id);
            })
            ->where('rule_type', 'manual')
            ->orderBy('priority', 'asc')
            ->first();
    }

    /**
     * Get applicable rule untuk habit (tanpa item) berdasarkan waktu.
     */
    public function getApplicableRuleForHabit(
        Habit $habit,
        School $school,
        Carbon $submittedAt
    ): ?HabitRule {
        $time = $submittedAt->format('H:i:s');

        return HabitRule::where('habit_id', $habit->id)
            ->whereNull('habit_item_id')
            ->where(function ($q) use ($school) {
                $q->whereNull('school_id')
                  ->orWhere('school_id', $school->id);
            })
            ->where('rule_type', 'time')
            ->where('start_time', '<=', $time)
            ->where('end_time', '>=', $time)
            ->orderBy('priority', 'asc')
            ->first();
    }

    /**
     * Get manual rule untuk habit (tanpa item).
     */
    public function getManualRuleForHabit(Habit $habit, School $school): ?HabitRule
    {
        return HabitRule::where('habit_id', $habit->id)
            ->whereNull('habit_item_id')
            ->where(function ($q) use ($school) {
                $q->whereNull('school_id')
                  ->orWhere('school_id', $school->id);
            })
            ->where('rule_type', 'manual')
            ->orderBy('priority', 'asc')
            ->first();
    }
}