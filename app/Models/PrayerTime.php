<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrayerTime extends Model
{
    protected $fillable = [
        'school_id',
        'date',
        'subuh',
        'dzuhur',
        'ashar',
        'maghrib',
        'isya',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relationship to school.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get all prayer times as array.
     */
    public function getAllTimes(): array
    {
        return [
            'subuh' => $this->subuh,
            'dzuhur' => $this->dzuhur,
            'ashar' => $this->ashar,
            'maghrib' => $this->maghrib,
            'isya' => $this->isya,
        ];
    }

    /**
     * Get prayer time by name.
     */
    public function getPrayerTime(string $prayerName): ?string
    {
        $field = strtolower($prayerName);
        
        if (in_array($field, ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'])) {
            return $this->$field;
        }

        return null;
    }
}