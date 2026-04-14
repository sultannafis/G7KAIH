<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prayer_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('subuh')->nullable();
            $table->time('dzuhur')->nullable();
            $table->time('ashar')->nullable();
            $table->time('maghrib')->nullable();
            $table->time('isya')->nullable();
            $table->timestamps();

            // Unique constraint untuk school_id + date
            $table->unique(['school_id', 'date']);
            
            // Index untuk query performance
            $table->index(['school_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prayer_times');
    }
};