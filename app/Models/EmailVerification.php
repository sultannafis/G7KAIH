<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory;

    protected $table = 'email_verifications';
    
    protected $fillable = [
        'email',
        'code', // Changed from 'otp_code' to match migration
        'expired_at',
        'verified_at'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'verified_at' => 'datetime'
    ];
}