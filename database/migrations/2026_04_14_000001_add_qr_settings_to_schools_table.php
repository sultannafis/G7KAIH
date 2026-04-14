<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Background kartu QR (custom per sekolah)
            $table->string('qr_bg_path')->nullable()->after('logo_path');

            // Logo kedua (default: YBM PLN, bisa diganti)
            $table->string('qr_logo2_path')->nullable()->after('qr_bg_path');

            // Tampilkan logo 1 (logo sekolah) atau tidak
            $table->boolean('qr_show_logo1')->default(true)->after('qr_logo2_path');

            // Tampilkan logo 2 (YBM PLN / custom) atau tidak
            $table->boolean('qr_show_logo2')->default(true)->after('qr_show_logo1');

            // Posisi logo 1: persentase dari kiri & atas (dalam area header kartu)
            $table->float('qr_logo1_x', 5, 2)->default(25.00)->after('qr_show_logo2');
            $table->float('qr_logo1_y', 5, 2)->default(40.00)->after('qr_logo1_x');

            // Posisi logo 2: persentase dari kiri & atas
            $table->float('qr_logo2_x', 5, 2)->default(75.00)->after('qr_logo1_y');
            $table->float('qr_logo2_y', 5, 2)->default(40.00)->after('qr_logo2_x');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'qr_bg_path',
                'qr_logo2_path',
                'qr_show_logo1',
                'qr_show_logo2',
                'qr_logo1_x',
                'qr_logo1_y',
                'qr_logo2_x',
                'qr_logo2_y',
            ]);
        });
    }
};
