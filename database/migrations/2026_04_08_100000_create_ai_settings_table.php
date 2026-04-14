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
            $table->string('app_name')->default('G7KAIH');
            $table->timestamps();
        });

        // Insert default row
        DB::table('ai_settings')->insert([
            'is_enabled'  => false,
            'app_name'    => 'G7KAIH',
            'app_context' => 'G7KAIH adalah aplikasi habit tracking untuk sekolah. Siswa mencatat kebiasaan harian seperti sholat, olahraga, dan belajar. Guru dan orang tua bisa memantau dan memvalidasi kebiasaan siswa.',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};
