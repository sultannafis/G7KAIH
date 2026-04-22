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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('npsn')->unique();
            $table->string('timezone')->default('Asia/Jakarta');
            $table->boolean('is_wa_enabled')->default(false);
            $table->boolean('is_email_enabled')->default(true);
            $table->string('qr_bg_path')->nullable();
            $table->string('qr_logo1_path')->nullable();
            $table->string('qr_logo2_path')->nullable();
            $table->boolean('qr_show_logo1')->default(true);
            $table->boolean('qr_show_logo2')->default(true);
            $table->float('qr_logo1_x', 5, 2)->default(25.00);
            $table->float('qr_logo1_y', 5, 2)->default(40.00);
            $table->float('qr_logo2_x', 5, 2)->default(75.00);
            $table->float('qr_logo2_y', 5, 2)->default(40.00);
            $table->decimal('qr_logo1_size', 5, 2)->nullable()->default(15);
            $table->decimal('qr_logo2_size', 5, 2)->nullable()->default(15);
            $table->enum('status', ['pending', 'active', 'rejected', 'in_active'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
