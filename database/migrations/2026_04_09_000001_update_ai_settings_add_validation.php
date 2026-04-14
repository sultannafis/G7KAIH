<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_settings', function (Blueprint $table) {
            // Validasi AI foto
            $table->boolean('ai_validation_enabled')->default(false)->after('app_context');
            $table->text('ai_validation_prompt')->nullable()->after('ai_validation_enabled');
            // 'all' atau 'selected' — menentukan apakah semua sekolah atau pilihan
            $table->enum('ai_validation_scope', ['all', 'selected'])->default('all')->after('ai_validation_prompt');
        });

        // Tabel pivot: sekolah mana yang boleh pakai validasi AI
        Schema::create('ai_validation_schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique('school_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_validation_schools');
        Schema::table('ai_settings', function (Blueprint $table) {
            $table->dropColumn(['ai_validation_enabled', 'ai_validation_prompt', 'ai_validation_scope']);
        });
    }
};
