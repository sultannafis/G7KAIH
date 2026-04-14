<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitSubmission extends Model
{
    protected $fillable = [
        'student_id',
        'g7_kaih_class_id',
        'habit_id',
        'habit_item_id',
        'habit_rule_id',
        'submission_date',
        'submitted_at',
        'proof_file',
        'description',
        'status',
        'point',
        'ai_needs_review',
        'ai_confidence',
    ];

    protected $casts = [
        'submitted_at'    => 'datetime',
        'submission_date' => 'date',
        'ai_needs_review' => 'boolean',
    ];

    // ─── Relasi ───────────────────────────────────────────────

    public function student()      { return $this->belongsTo(Student::class); }
    public function habit()        { return $this->belongsTo(Habit::class); }
    public function habitItem()    { return $this->belongsTo(HabitItem::class); }
    public function rule()         { return $this->belongsTo(HabitRule::class, 'habit_rule_id'); }
    public function validations()  { return $this->hasMany(HabitValidation::class); }
    public function g7kaihClass()  { return $this->belongsTo(G7KaihClass::class, 'g7_kaih_class_id'); }

    public function selectedActivities()
    {
        return $this->belongsToMany(
            HabitItem::class,
            'habit_submission_items',
            'habit_submission_id',
            'habit_item_id'
        )->withTimestamps();
    }

    public function mediaFiles()
    {
        return $this->belongsToMany(MediaFile::class, 'habit_submission_media')
            ->withTimestamps();
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function isMultiSelect(): bool
    {
        return $this->habit?->is_multi_select ?? false;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_parent'   => 'Menunggu Orang Tua',
            'parent_rejected'  => 'Ditolak Orang Tua',
            'pending_ai'       => 'Diproses AI',
            'ai_valid'         => $this->ai_needs_review ? 'Divalidasi AI (Perlu Review)' : 'Divalidasi AI',
            'pending_teacher'  => 'Menunggu Guru',
            'teacher_valid'    => 'Disetujui Guru',
            'teacher_rejected' => 'Ditolak Guru',
            default            => ucfirst($this->status),
        };
    }
}
