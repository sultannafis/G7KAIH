<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitItem extends Model
{
    protected $fillable = [
        'habit_id',
        'name',
        'description',
        'is_active',
        'is_activity_option', // true = pilihan kegiatan multi-select (TIPE B)
    ];

    protected $casts = [
        'is_active'          => 'boolean',
        'is_activity_option' => 'boolean',
    ];

    // ─── Relasi ───────────────────────────────────────────────

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    /**
     * Rules milik item ini (TIPE A: rules per item, misal per waktu sholat).
     */
    public function rules()
    {
        return $this->hasMany(HabitRule::class);
    }

    /**
     * Submission yang mencantumkan item ini sebagai single item (TIPE A).
     */
    public function submissions()
    {
        return $this->hasMany(HabitSubmission::class);
    }

    /**
     * Submission yang memilih item ini via pivot (TIPE B multi-select).
     */
    public function submissionsAsActivity()
    {
        return $this->belongsToMany(
            HabitSubmission::class,
            'habit_submission_items',
            'habit_item_id',
            'habit_submission_id'
        )->withTimestamps();
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function isActivityOption(): bool
    {
        return (bool) $this->is_activity_option;
    }
}