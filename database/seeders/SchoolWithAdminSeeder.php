<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\School;
use App\Models\User;
use App\Models\Address;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class SchoolWithAdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // =========================
            // 1. Ambil Data Wilayah
            // =========================
            $province = Province::where('name', 'JAWA BARAT')->first();
            $city     = City::where('name', 'KOTA DEPOK')->first();
            $district = District::where('name', 'LIMO')->first();
            $village  = Village::where('name', 'KRUKUT')->first();

            // =========================
            // 2. Buat School
            // =========================
            $school = School::create([
                'name'     => 'SMKS INFORMATIKA UTAMA',
                'npsn'     => '20252317',
                'timezone' => 'Asia/Jakarta',
                'status'   => 'active',
            ]);

            // =========================
            // 3. Buat Address (Morph)
            // =========================
            $school->addresses()->create([
                'province_id'   => $province?->id,
                'city_id'       => $city?->id,
                'district_id'   => $district?->id,
                'village_id'    => $village?->id,
                'address_detail'=> 'jalan haji iman',
                'postal_code'   => '16515',
                'latitude'      => -6.348179,
                'longitude'     => 106.784449,
            ]);

            // =========================
            // 4. Buat Admin Sekolah
            // =========================
            User::create([
                'school_id'         => $school->id,
                'name'              => 'Sultan Nafis - Admin Sekolah',
                'email'             => 'sultan.nafis65@smk.belajar.id',
                'phone_number'      => '089654557984',
                'religion'          => 'Islam',
                'role'              => 'admin',
                'password'          => Hash::make('12345678'),
                'is_active'         => true,
                'email_verified_at' => now(),
            ]);
        });
    }
}