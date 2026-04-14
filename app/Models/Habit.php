<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'description',
        'is_active',
        'is_multi_select',
        'max_select',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'is_multi_select' => 'boolean',
    ];

    // ─── Relasi ───────────────────────────────────────────────

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Semua habit items.
     */
    public function items()
    {
        return $this->hasMany(HabitItem::class);
    }

    /**
     * Activity options (is_activity_option = true) — untuk TIPE B Multi-Select.
     * Ini adalah daftar pilihan kegiatan yang bisa dipilih siswa.
     */
    public function activityOptions()
    {
        return $this->hasMany(HabitItem::class)
            ->where('is_activity_option', true)
            ->where('is_active', true)
            ->orderBy('name');
    }

    /**
     * Regular items (is_activity_option = false) — untuk TIPE A berbasis waktu.
     * Contoh: Sholat Subuh, Sholat Dzuhur, dll.
     */
    public function regularItems()
    {
        return $this->hasMany(HabitItem::class)
            ->where('is_activity_option', false)
            ->where('is_active', true);
    }

    /**
     * Semua rules milik habit ini.
     */
    public function rules()
    {
        return $this->hasMany(HabitRule::class);
    }

    /**
     * Rules langsung di level habit (habit_item_id = null).
     *
     * Untuk TIPE B (multi_select): berisi rules berdasarkan min_items_selected.
     * Untuk TIPE A tanpa item: berisi rules manual/time.
     */
    public function directRules()
    {
        return $this->hasMany(HabitRule::class)
            ->whereNull('habit_item_id')
            ->orderBy('priority');
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function isMultiSelect(): bool
    {
        return (bool) $this->is_multi_select;
    }
}