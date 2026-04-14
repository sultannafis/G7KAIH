<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = ['user_id', 'nip', 'nik'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function g7kaihClass()
    {
        return $this->hasOne(G7KaihClass::class, 'teacher_id');
    }

}
