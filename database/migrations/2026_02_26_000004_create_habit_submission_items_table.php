<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pivot table untuk menyimpan activity options yang dipilih siswa
     * dalam satu submission habit bertipe multi-select.
     *
     * Contoh:
     * Siswa submit "Kegiatan Bermasyarakat" dan memilih:
     * - Kerja Bakti
     * - Rapat OSIS
     * - Pos Ronda
     *
     * Maka di tabel ini akan ada 3 baris dengan submission_id yang sama.
     */
    public function up(): void
    {
        Schema::create('habit_submission_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habit_submission_id')->constrained('habit_submissions')->cascadeOnDelete();
            $table->foreignId('habit_item_id')->constrained('habit_items')->cascadeOnDelete();
            $table->timestamps();

            // Satu submission tidak boleh memilih item yang sama dua kali
            $table->unique(['habit_submission_id', 'habit_item_id'],'unique_submission_item'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habit_submission_items');
    }
};
