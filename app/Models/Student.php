<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'nisn', 'nis', 'grade_level', 'class_name', 'major',
        'g7_kaih_class_id',
        'gender',
        'total_point',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parents()
    {
        return $this->hasMany(Parents::class);
    }

    public function habitSubmissions()
    {
        return $this->hasMany(HabitSubmission::class);
    }

    /**
     * Fix #3: Sebutkan foreign key secara eksplisit karena nama kolom
     * adalah g7_kaih_class_id, bukan g7_kaih_class_id yang Laravel
     * tidak bisa tebak dari nama method "g7kaihClass".
     */
    public function g7kaihClass()
    {
        return $this->belongsTo(G7KaihClass::class, 'g7_kaih_class_id');
    }

    public function recalculateTotalPoint(): void
    {
        $this->total_point = $this->habitSubmissions()
            ->whereIn('status', ['teacher_valid', 'ai_valid'])
            ->sum('point');
        $this->save();
    }
}