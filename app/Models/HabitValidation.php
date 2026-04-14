<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitValidation extends Model
{
    protected $fillable = [
        'habit_submission_id', 'validator_type',
        'validator_id', 'status', 'reason'
    ];

    public function submission()
    {
        return $this->belongsTo(HabitSubmission::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }
}

