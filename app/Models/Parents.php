<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Parents extends Model
{
    protected $table = 'parents';

    protected $fillable = ['user_id', 'student_id', 'family_relationship', 'login_code'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($parent) {
            if (empty($parent->login_code)) {
                $parent->login_code = self::generateLoginCode();
            }
        });
    }

    /**
     * Generate kode login unik 8 digit angka
     */
    public static function generateLoginCode(): string
    {
        do {
            $code = str_pad(random_int(10000000, 99999999), 8, '0', STR_PAD_LEFT);
        } while (self::where('login_code', $code)->exists());

        return $code;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
