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
        Schema::create('habit_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habit_submission_id')->constrained()->cascadeOnDelete();
            $table->enum('validator_type', ['parent', 'ai', 'teacher']);
            $table->foreignId('validator_id')->nullable(); // user_id (parent/guru)
            $table->enum('status', ['approved', 'rejected']);
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_validations');
    }
};
