<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSetting extends Model
{
    protected $fillable = [
        'is_enabled',
        'app_name',
        'app_context',
        'ai_chat_system_prompt',
        'ai_validation_enabled',
        'ai_validation_prompt',
        'ai_validation_scope',
    ];

    protected $casts = [
        'is_enabled'            => 'boolean',
        'ai_validation_enabled' => 'boolean',
    ];

    public static function instance(): self
    {
        return self::firstOrCreate([], [
            'is_enabled'             => false,
            'app_name'               => 'G7KAIH',
            'app_context'            => 'G7KAIH adalah aplikasi habit tracking untuk sekolah.',
            'ai_chat_system_prompt'  => "Nama kamu adalah G7KAIH AI Assistant. Kamu bertugas membantu guru dan siswa dalam mengelola habit (kebiasaan) di sekolah.\n\nTUGAS UTAMA:\n- Membantu membuatkan 'Nama Habit' yang menarik dan 'Deskripsi Habit' yang jelas.\n- Jika user meminta bantuan mengisi form, berikan saran yang singkat dan padat.\n- Jika user bertanya tentang fitur aplikasi, jawab bahwa aplikasi ini adalah Sistem Manajemen Habit Sekolah.\n\nATURAN KHUSUS:\n- Selalu gunakan Bahasa Indonesia yang ramah namun sopan.\n- Jika user ingin membuat habit, arahkan untuk mengisi nama habit dan deskripsi.\n- Jika kamu diminta memberikan data untuk form, berikan jawaban dalam teks biasa yang mudah disalin.",
            'ai_validation_enabled'  => false,
            'ai_validation_prompt'   => "Kamu adalah Validator Habit Digital G7KAIH. Tugasmu memeriksa foto bukti kegiatan siswa.\n\nANALISIS FOTO BERDASARKAN:\n- Relevansi: Apakah foto sesuai dengan nama habit '[NAMA_HABIT]'?\n- Keaslian: Apakah ini foto asli (bukan screenshot internet, bukan foto layar komputer, bukan foto yang sama berulang kali)?\n- Konteks: Apakah lingkungan/waktu di foto masuk akal dangan kegiatan tersebut?",
            'ai_validation_scope'    => 'all',
        ]);
    }

    public function schoolCanUseAiValidation(int $schoolId): bool
    {
        if (! $this->ai_validation_enabled) return false;
        if ($this->ai_validation_scope === 'all') return true;
        return \DB::table('ai_validation_schools')->where('school_id', $schoolId)->exists();
    }
}
