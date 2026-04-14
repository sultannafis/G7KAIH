<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'school_id',
        'name',
        'email',
        'phone_number',
        'religion',
        'role',
        'password',
        'is_active',
        'email_verified_at',
        'avatar_url',
        'signature_url', 
        'signature_media_id',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast attributes
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::updated(function (User $user) {
            if ($user->isDirty('is_active')) {
                try {
                    if ($user->is_active) {
                        app(\App\Services\Notification\UserEventNotificationService::class)->userActivated($user);
                    } else {
                        app(\App\Services\Notification\UserEventNotificationService::class)->userDeactivated($user);
                    }
                } catch (\Throwable $e) {
                    \Log::error('Gagal mengirim notifikasi aktivasi user: ' . $e->getMessage());
                }
            }
        });
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        try {
            app(\App\Services\Notification\UserEventNotificationService::class)->passwordResetRequested($this, $token);
        } catch (\Throwable $e) {
            \Log::error('Gagal mengirim notifikasi reset password: ' . $e->getMessage());
        }
    }

    /* =========================
     |        RELATIONS
     ========================= */

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function parent()
    {
        return $this->hasOne(Parents::class);
    }

    public function validations()
    {
        return $this->hasMany(HabitValidation::class, 'validator_id');
    }

     /**
     * Generate barcode data for student
     */
    public function generateBarcodeData()
    {
        // Format: STUDENT-{NIS}-{NISN}
        return "STUDENT-{$this->nis}-{$this->nisn}";
    }

    /**
     * Generate barcode image URL
     */
    public function getBarcodeUrl()
    {
        $barcodeData = $this->generateBarcodeData();
        $encodedData = urlencode($barcodeData);
        
        // Use a barcode generator service or library
        return "https://barcode.tec-it.com/barcode.ashx?data={$encodedData}&code=Code128&dpi=96";
    }

    /**
     * Get the user's avatar URL or a fallback a sky blue generated avatar.
     */
    public function getAvatarUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $name = urlencode(trim($this->name));
        // Sky-500 background with white text
        return "https://ui-avatars.com/api/?name={$name}&background=0ea5e9&color=ffffff&bold=true";
    }
}
