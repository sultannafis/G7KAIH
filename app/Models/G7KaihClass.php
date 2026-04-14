<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class G7KaihClass extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'description',
        'academic_year',
        'teacher_id',
        'is_active',
        'created_by',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
