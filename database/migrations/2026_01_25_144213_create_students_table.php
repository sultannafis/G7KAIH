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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('g7_kaih_class_id')->nullable()->constrained('g7_kaih_classes')->nullOnDelete();
            $table->string('nis')->nullable();
            $table->string('nisn')->nullable();
            $table->enum('grade_level', ['X', 'XI', 'XII'])->nullable();
            $table->string('class_name')->nullable(); // A / B / IPA1
            $table->string('major')->nullable(); // RPL / TKJ / IPA
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->integer('total_point')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};