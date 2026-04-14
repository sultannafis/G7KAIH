<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitRule extends Model
{
    protected $fillable = [
        'habit_id',
        'habit_item_id',
        'school_id',
        'name',
        'rule_type',         // 'time' | 'manual'
        'start_time',        // untuk rule_type = time (TIPE A)
        'end_time',          // untuk rule_type = time (TIPE A)
        'min_items_selected', // untuk TIPE B multi-select (berapa min item yang dipilih)
        'point',
        'priority',
        'require_parent_validation',
        'allow_ai_validation',
    ];

    protected $casts = [
        'require_parent_validation' => 'boolean',
        'allow_ai_validation'       => 'boolean',
    ];

    // ─── Relasi ───────────────────────────────────────────────

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    public function habitItem()
    {
        return $this->belongsTo(HabitItem::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function submissions()
    {
        return $this->hasMany(HabitSubmission::class);
    }
}