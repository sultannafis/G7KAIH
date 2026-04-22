<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'name',
        'npsn',
        'timezone',
        'status',
        'rejection_reason',
        'is_wa_enabled',
        'is_email_enabled',
        // QR Card settings
        'qr_bg_path',
        'qr_logo1_path',
        'qr_logo2_path',
        'qr_show_logo1',
        'qr_show_logo2',
        'qr_logo1_x',
        'qr_logo1_y',
        'qr_logo2_x',
        'qr_logo2_y',
        'qr_logo1_size',  // ← TAMBAH INI
        'qr_logo2_size',  // ← TAMBAH INI
    ];

    protected $casts = [
        'is_wa_enabled'   => 'boolean',
        'is_email_enabled'=> 'boolean',
        'qr_show_logo1'   => 'boolean',
        'qr_show_logo2'   => 'boolean',
        'qr_logo1_x'      => 'float',
        'qr_logo1_y'      => 'float',
        'qr_logo2_x'      => 'float',
        'qr_logo2_y'      => 'float',
        'qr_logo1_size'   => 'float',  // ← TAMBAH INI
        'qr_logo2_size'   => 'float',  // ← TAMBAH INI
    ];

    protected $with = ['addresses'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function habits()
    {
        return $this->hasMany(Habit::class);
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable')
                    ->with(['province', 'city', 'district', 'village']);
    }

    public function notificationTemplates()
    {
        return $this->hasMany(NotificationTemplate::class);
    }

    public function notificationLogs()
    {
        return $this->hasMany(NotificationLog::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'in_active');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function getLogoUrlAttribute()
    {
        return $this->qr_logo1_path
            ? asset('storage/' . $this->qr_logo1_path)
            : asset('images/logo smk.png');
    }

    public function getQrBgUrlAttribute()
    {
        return $this->qr_bg_path
            ? asset('storage/' . $this->qr_bg_path)
            : asset('images/background smk.png');
    }

    public function getQrLogo1UrlAttribute()
    {
        return $this->qr_logo1_path
            ? asset('storage/' . $this->qr_logo1_path)
            : $this->logo_url;
    }

    public function getQrLogo2UrlAttribute()
    {
        return $this->qr_logo2_path
            ? asset('storage/' . $this->qr_logo2_path)
            : asset('images/logo ybm pln.png');
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    protected static function booted()
    {
        static::updated(function (School $school) {
            if ($school->isDirty('status')) {
                try {
                    $oldStatus = $school->getOriginal('status');
                    $newStatus = $school->status;

                    if ($newStatus === 'in_active' && $oldStatus !== 'in_active') {
                        app(\App\Services\Notification\UserEventNotificationService::class)->schoolSuspended($school);
                    } elseif ($newStatus === 'active' && $oldStatus === 'in_active') {
                        app(\App\Services\Notification\UserEventNotificationService::class)->schoolActivated($school);
                    }
                } catch (\Throwable $e) {
                    \Log::error('Gagal mengirim notifikasi status sekolah: ' . $e->getMessage());
                }
            }
        });
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}