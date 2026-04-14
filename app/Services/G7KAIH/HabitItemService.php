<?php

namespace App\Services\G7KAIH;

use App\Models\Habit;
use App\Models\HabitItem;
use App\Models\School;
use App\Services\Prayer\PrayerTimeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HabitItemService
{
    protected $prayerTimeService;
    protected $habitRuleService;

    public function __construct(
        PrayerTimeService $prayerTimeService,
        HabitRuleService $habitRuleService
    ) {
        $this->prayerTimeService = $prayerTimeService;
        $this->habitRuleService = $habitRuleService;
    }

    /**
     * Create a new habit item.
     */
    public function createHabitItem(Habit $habit, array $data): HabitItem
    {
        return DB::transaction(function () use ($habit, $data) {
            $data['habit_id'] = $habit->id;
            $data['is_active'] = $data['is_active'] ?? true;

            return HabitItem::create($data);
        });
    }

    /**
     * Update an existing habit item.
     */
    public function updateHabitItem(HabitItem $item, array $data): HabitItem
    {
        return DB::transaction(function () use ($item, $data) {
            $item->update($data);
            return $item->fresh();
        });
    }

    /**
     * Delete a habit item.
     */
    public function deleteHabitItem(HabitItem $item): bool
    {
        return DB::transaction(function () use ($item) {
            return $item->delete();
        });
    }

    /**
     * Toggle habit item active status.
     */
    public function toggleHabitItemStatus(HabitItem $item): HabitItem
    {
        return DB::transaction(function () use ($item) {
            $item->update(['is_active' => !$item->is_active]);
            return $item->fresh();
        });
    }

    /**
     * Create prayer rules automatically for a prayer habit item.
     * Digunakan saat membuat item sholat baru (Subuh, Dzuhur, dll)
     */
    public function createPrayerRules(HabitItem $item, School $school): array
    {
        return DB::transaction(function () use ($item, $school) {
            try {
                // Pastikan item sudah di-load dengan relasi habit
                $item->load('habit');
                
                if (!$item->habit) {
                    throw new \Exception("Habit tidak ditemukan untuk item {$item->id}");
                }
                
                // Sync waktu sholat dari API
                $today = Carbon::now($school->timezone)->format('d-m-Y');
                $prayerTime = $this->prayerTimeService->sync($school, $today);

                // Generate rules untuk item ini
                return $this->habitRuleService->generatePrayerRulesForItem(
                    $item,
                    $school,
                    $prayerTime
                );
            } catch (\Exception $e) {
                Log::error("Failed to create prayer rules for item {$item->id}: " . $e->getMessage(), [
                    'item_id' => $item->id,
                    'school_id' => $school->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Sync prayer times and update rules for a prayer item.
     */
    public function syncPrayerTimesForItem(HabitItem $item, School $school): array
    {
        return DB::transaction(function () use ($item, $school) {
            try {
                // Pastikan item sudah di-load dengan relasi habit
                $item->load('habit');
                
                if (!$item->habit) {
                    throw new \Exception("Habit tidak ditemukan untuk item {$item->id}");
                }
                
                Log::info("Starting prayer time sync for item", [
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'habit_id' => $item->habit_id,
                    'school_id' => $school->id,
                ]);
                
                // Sync waktu sholat dari API
                $today = Carbon::now($school->timezone)->format('d-m-Y');
                $prayerTime = $this->prayerTimeService->sync($school, $today);
                
                Log::info("Prayer time fetched successfully", [
                    'date' => $today,
                    'prayer_times' => [
                        'subuh' => $prayerTime->subuh,
                        'dzuhur' => $prayerTime->dzuhur,
                        'ashar' => $prayerTime->ashar,
                        'maghrib' => $prayerTime->maghrib,
                        'isya' => $prayerTime->isya,
                    ]
                ]);

                // Update existing rules atau buat baru
                $rules = $this->habitRuleService->updatePrayerRulesForItem(
                    $item,
                    $school,
                    $prayerTime
                );
                
                Log::info("Prayer rules generated successfully", [
                    'item_id' => $item->id,
                    'rules_count' => count($rules),
                ]);
                
                return $rules;
                
            } catch (\Exception $e) {
                Log::error("Failed to sync prayer times for item {$item->id}: " . $e->getMessage(), [
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'school_id' => $school->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Check if habit item is a prayer item.
     */
    public function isPrayerItem(HabitItem $item): bool
    {
        $prayerNames = ['subuh', 'fajr', 'dzuhur', 'dhuhr', 'ashar', 'asr', 'maghrib', 'isya', 'isha'];
        $itemName = strtolower($item->name);

        foreach ($prayerNames as $prayer) {
            if (str_contains($itemName, $prayer)) {
                return true;
            }
        }

        return false;
    }

    /**
 * Check if habit is prayer habit.
 */
public function isPrayerHabit(Habit $habit): bool
{
    $prayerKeywords = ['sholat', 'solat', 'ibadah', 'prayer'];
    $habitName = strtolower($habit->name);

    foreach ($prayerKeywords as $keyword) {
        if (str_contains($habitName, $keyword)) {
            return true;
        }
    }

    return false;
}

    /**
     * Get prayer name mapping (Indonesian to API format).
     */
    public function getPrayerNameMapping(): array
    {
        return [
            'subuh' => 'Fajr',
            'fajr' => 'Fajr',
            'dzuhur' => 'Dhuhr',
            'dhuhr' => 'Dhuhr',
            'ashar' => 'Asr',
            'asr' => 'Asr',
            'maghrib' => 'Maghrib',
            'isya' => 'Isha',
            'isha' => 'Isha',
        ];
    }

    /**
     * Get API prayer field name from habit item name.
     */
    public function getApiPrayerFieldName(HabitItem $item): ?string
    {
        $itemName = strtolower($item->name);
        $mapping = $this->getPrayerNameMapping();

        foreach ($mapping as $indonesian => $api) {
            if (str_contains($itemName, $indonesian)) {
                return strtolower($api);
            }
        }

        return null;
    }
}