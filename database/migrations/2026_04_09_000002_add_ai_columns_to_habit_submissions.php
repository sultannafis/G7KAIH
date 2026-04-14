<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('habit_submissions', function (Blueprint $table) {
            $table->boolean('ai_needs_review')->default(false)->after('point');
            $table->unsignedTinyInteger('ai_confidence')->nullable()->after('ai_needs_review');
        });

        // Tambah status baru ke enum jika belum ada
        // (pending_ai & ai_valid mungkin sudah ada, tapi kita pastikan)
        DB::statement("ALTER TABLE habit_submissions MODIFY COLUMN status ENUM(
            'pending_parent',
            'parent_rejected',
            'pending_ai',
            'ai_valid',
            'pending_teacher',
            'teacher_valid',
            'teacher_rejected'
        ) NOT NULL DEFAULT 'pending_parent'");
    }

    public function down(): void
    {
        Schema::table('habit_submissions', function (Blueprint $table) {
            $table->dropColumn(['ai_needs_review', 'ai_confidence']);
        });
    }
};
