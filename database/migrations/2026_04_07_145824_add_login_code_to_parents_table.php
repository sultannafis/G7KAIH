<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->string('login_code')->unique()->nullable()->after('family_relationship');
        });

        // Generate login_code untuk data yang sudah ada
        $parents = DB::table('parents')->whereNull('login_code')->get();
        foreach ($parents as $parent) {
            $code = $this->generateUniqueCode();
            DB::table('parents')->where('id', $parent->id)->update(['login_code' => $code]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn('login_code');
        });
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = str_pad(random_int(10000000, 99999999), 8, '0', STR_PAD_LEFT);
        } while (DB::table('parents')->where('login_code', $code)->exists());

        return $code;
    }
};
