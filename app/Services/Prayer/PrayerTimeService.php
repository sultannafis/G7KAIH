<?php

namespace App\Services\Prayer;

use App\Models\PrayerTime;
use App\Models\School;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PrayerTimeService
{
    /**
     * Sync prayer times from API and save to database.
     */
    public function sync(School $school, string $date): PrayerTime
    {
        // Check if prayer time already exists
        $existing = PrayerTime::where('school_id', $school->id)
            ->where('date', Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d'))
            ->first();

        if ($existing) {
            return $existing;
        }

        // Fetch from API
        $prayerData = $this->fetchFromApi($school, $date);

        // Create new prayer time record
        return PrayerTime::create([
            'school_id' => $school->id,
            'date' => Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d'),
            'subuh' => $prayerData['subuh'],
            'dzuhur' => $prayerData['dzuhur'],
            'ashar' => $prayerData['ashar'],
            'maghrib' => $prayerData['maghrib'],
            'isya' => $prayerData['isya'],
        ]);
    }

    /**
     * Fetch prayer times from Aladhan API.
     */
    protected function fetchFromApi(School $school, string $date): array
    {
        // Get school address with coordinates
        $address = $school->addresses->first();

        if (!$address || !$address->hasCoordinates()) {
            throw new \Exception('Koordinat sekolah belum diatur. Silakan lengkapi alamat sekolah dengan latitude dan longitude.');
        }

        try {
            $response = Http::timeout(10)->get(config('services.aladhan.base_url') . "/timings/{$date}", [
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
                'method' => config('services.aladhan.method', 11), // Kemenag RI
                'school' => config('services.aladhan.school', 0),
                'timezone' => $school->timezone,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Gagal mengambil data dari API Aladhan: ' . $response->status());
            }

            $data = $response->json();

            if (!isset($data['data']['timings'])) {
                throw new \Exception('Format response API tidak valid.');
            }

            $timings = $data['data']['timings'];

            // Convert to 24-hour format and extract only HH:mm
            return [
                'subuh' => $this->formatTime($timings['Fajr']),
                'dzuhur' => $this->formatTime($timings['Dhuhr']),
                'ashar' => $this->formatTime($timings['Asr']),
                'maghrib' => $this->formatTime($timings['Maghrib']),
                'isya' => $this->formatTime($timings['Isha']),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to fetch prayer times from API', [
                'school_id' => $school->id,
                'date' => $date,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal mengambil waktu sholat: ' . $e->getMessage());
        }
    }

    /**
     * Format time from API response (HH:mm or HH:mm (TIMEZONE)).
     */
    protected function formatTime(string $time): string
    {
        // Remove timezone info if exists: "04:30 (WIB)" -> "04:30"
        $time = trim(explode('(', $time)[0]);

        // Validate format
        if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
            throw new \Exception("Invalid time format: {$time}");
        }

        return $time;
    }

    /**
     * Sync prayer times for multiple days.
     */
    public function syncMultipleDays(School $school, Carbon $startDate, int $days = 7): array
    {
        $results = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->format('d-m-Y');
            
            try {
                $results[] = $this->sync($school, $date);
            } catch (\Exception $e) {
                Log::error("Failed to sync prayer times for date {$date}", [
                    'school_id' => $school->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }

    /**
     * Get today's prayer times for a school.
     */
    public function getTodayPrayerTimes(School $school): ?PrayerTime
    {
        $today = Carbon::now($school->timezone)->format('d-m-Y');
        
        try {
            return $this->sync($school, $today);
        } catch (\Exception $e) {
            Log::error('Failed to get today prayer times', [
                'school_id' => $school->id,
                'error' => $e->getMessage(),
            ]);
            
            return null;
        }
    }

    /**
     * Force refresh prayer times from API.
     */
    public function forceRefresh(School $school, string $date): PrayerTime
    {
        // Delete existing record
        PrayerTime::where('school_id', $school->id)
            ->where('date', Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d'))
            ->delete();

        // Fetch fresh data
        return $this->sync($school, $date);
    }
}