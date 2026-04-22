<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(false);
            $table->text('app_context')->nullable(); // Deskripsi aplikasi untuk AI
            $table->text('ai_chat_system_prompt')->nullable();
            $table->boolean('ai_validation_enabled')->default(false);
            $table->text('ai_validation_prompt')->nullable();
            $table->enum('ai_validation_scope', ['all', 'selected'])->default('all');
            $table->string('app_name')->default('G7KAIH');
            $table->timestamps();
        });

        Schema::create('ai_validation_schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique('school_id');
        });

        // Insert default row
        DB::table('ai_settings')->insert([
            'is_enabled'  => false,
            'app_name'    => 'G7KAIH',
            'app_context' => 'G7KAIH adalah aplikasi habit tracking untuk sekolah. Siswa mencatat kebiasaan harian seperti sholat, olahraga, dan belajar. Guru dan orang tua bisa memantau dan memvalidasi kebiasaan siswa.',
            'ai_chat_system_prompt' => "Nama kamu adalah G7KAIH AI Assistant. Kamu bertugas membantu guru dan siswa dalam mengelola habit (kebiasaan) di sekolah.\n\nTUGAS UTAMA:\n- Membantu membuatkan 'Nama Habit' yang menarik dan 'Deskripsi Habit' yang jelas.\n- Jika user meminta bantuan mengisi form, berikan saran yang singkat dan padat.\n- Jika user bertanya tentang fitur aplikasi, jawab bahwa aplikasi ini adalah Sistem Manajemen Habit Sekolah.\n\nATURAN KHUSUS:\n- Selalu gunakan Bahasa Indonesia yang ramah namun sopan.\n- Jika user ingin membuat habit, arahkan untuk mengisi nama habit dan deskripsi.\n- Jika kamu diminta memberikan data untuk form, berikan jawaban dalam teks biasa yang mudah disalin.",
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_validation_schools');
        Schema::dropIfExists('ai_settings');
    }
};
