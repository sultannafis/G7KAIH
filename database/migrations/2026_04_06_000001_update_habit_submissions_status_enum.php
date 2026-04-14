<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ubah kolom status habit_submissions:
     * Hapus nilai AI (pending_ai, ai_valid, ai_rejected),
     * tambahkan pending_teacher sebagai pengganti direct ke guru.
     */
    public function up(): void
    {
        // 1. Migrasi data lama: semua status AI → pending_teacher
        DB::statement("
            UPDATE habit_submissions
            SET status = 'pending_teacher'
            WHERE status IN ('pending_ai', 'ai_valid', 'ai_rejected')
        ");

        // 2. Ubah definisi ENUM di kolom status
        DB::statement("
            ALTER TABLE habit_submissions
            MODIFY COLUMN status ENUM(
                'pending_parent',
                'parent_rejected',
                'pending_teacher',
                'teacher_valid',
                'teacher_rejected'
            ) NOT NULL DEFAULT 'pending_parent'
        ");
    }

    public function down(): void
    {
        // Kembalikan ke enum lama (termasuk nilai AI)
        DB::statement("
            ALTER TABLE habit_submissions
            MODIFY COLUMN status ENUM(
                'pending_parent',
                'parent_rejected',
                'pending_ai',
                'ai_valid',
                'ai_rejected',
                'teacher_valid',
                'teacher_rejected'
            ) NOT NULL DEFAULT 'pending_parent'
        ");

        // Kembalikan pending_teacher → pending_ai (approx)
        DB::statement("
            UPDATE habit_submissions
            SET status = 'pending_ai'
            WHERE status = 'pending_teacher'
        ");
    }
};
