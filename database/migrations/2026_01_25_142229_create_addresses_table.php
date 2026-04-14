<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->morphs('addressable');
            $table->foreignId('province_id')->nullable()->constrained('indonesia_provinces');
            $table->foreignId('city_id')->nullable()->constrained('indonesia_cities');
            $table->foreignId('district_id')->nullable()->constrained('indonesia_districts');
            $table->foreignId('village_id')->nullable()->constrained('indonesia_villages');
            $table->text('address_detail')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
