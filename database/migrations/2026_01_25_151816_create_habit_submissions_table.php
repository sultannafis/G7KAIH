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
        Schema::create('habit_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('g7_kaih_class_id')->nullable()->constrained('g7_kaih_classes')->nullOnDelete();
            $table->foreignId('habit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('habit_item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('habit_rule_id')->nullable()->constrained()->nullOnDelete();
            $table->date('submission_date');
            $table->timestamp('submitted_at');
            $table->string('proof_file')->nullable(); // WAJIB untuk manual
            $table->text('description')->nullable();  // WAJIB untuk manual
            $table->enum('status', ['pending_parent', 'parent_rejected', 'pending_ai', 'ai_valid', 'teacher_valid', 'teacher_rejected'])->default('pending_parent');
            $table->integer('point')->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'habit_id', 'habit_item_id', 'submission_date'],'uniq_submission_daily');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_submissions');
    }
};
