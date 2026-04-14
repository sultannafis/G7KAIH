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
        Schema::table('schools', function (Blueprint $table) {
            $table->decimal('qr_logo1_size', 5, 2)->nullable()->default(15)->after('qr_logo2_y');
            $table->decimal('qr_logo2_size', 5, 2)->nullable()->default(15)->after('qr_logo1_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['qr_logo1_size', 'qr_logo2_size']);
        });
    }
};
