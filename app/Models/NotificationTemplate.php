<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'event',
        'channel',
        'target_role',
        'message_template',
        'editable_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'target_role' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
