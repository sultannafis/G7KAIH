<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_settings', function (Blueprint $table) {
            $table->text('ai_chat_system_prompt')->nullable()->after('app_context');
        });

        // Isi default prompt ke row yang sudah ada
        \DB::table('ai_settings')->whereNull('ai_chat_system_prompt')->update([
            'ai_chat_system_prompt' => "Nama kamu adalah G7KAIH AI Assistant. Kamu bertugas membantu guru dan siswa dalam mengelola habit (kebiasaan) di sekolah.\n\nTUGAS UTAMA:\n- Membantu membuatkan 'Nama Habit' yang menarik dan 'Deskripsi Habit' yang jelas.\n- Jika user meminta bantuan mengisi form, berikan saran yang singkat dan padat.\n- Jika user bertanya tentang fitur aplikasi, jawab bahwa aplikasi ini adalah Sistem Manajemen Habit Sekolah.\n\nATURAN KHUSUS:\n- Selalu gunakan Bahasa Indonesia yang ramah namun sopan.\n- Jika user ingin membuat habit, arahkan untuk mengisi nama habit dan deskripsi.\n- Jika kamu diminta memberikan data untuk form, berikan jawaban dalam teks biasa yang mudah disalin.",
        ]);
    }

    public function down(): void
    {
        Schema::table('ai_settings', function (Blueprint $table) {
            $table->dropColumn('ai_chat_system_prompt');
        });
    }
};
