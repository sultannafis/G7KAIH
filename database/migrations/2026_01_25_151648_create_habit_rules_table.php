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
        Schema::create('habit_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habit_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('habit_item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name'); // Tepat Waktu / Default / dll
            $table->enum('rule_type', ['time', 'manual']); 
            // time = pakai start/end
            // manual = langsung point
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedTinyInteger('min_items_selected')->nullable();
            $table->integer('point')->default(0);
            $table->integer('priority')->default(1);
            $table->boolean('require_parent_validation')->default(true);
            $table->boolean('allow_ai_validation')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_rules');
    }
};
