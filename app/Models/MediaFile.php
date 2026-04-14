<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'cloudinary_public_id',
        'url',
        'type',
        // CATATAN: kolom habit_submission_id di tabel media_files TIDAK dipakai
        // untuk relasi utama. Relasi ke submission pakai pivot: habit_submission_media
        // Jangan isi habit_submission_id langsung — pakai ->attach() via pivot.
    ];

    /**
     * Relasi ke HabitSubmission lewat pivot table habit_submission_media.
     * Ini adalah satu-satunya cara yang benar untuk mengambil submission dari media.
     */
    public function habitSubmissions()
    {
        return $this->belongsToMany(
            HabitSubmission::class,
            'habit_submission_media',
            'media_file_id',
            'habit_submission_id'
        )->withTimestamps();
    }
}