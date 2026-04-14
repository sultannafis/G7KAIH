<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MasterAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'sultannafis1324@gmail.com',
            ],
            [
                'school_id'   => null,
                'name'        => 'Master Admin',
                'phone_number'=> '089654557984',
                'religion'    => 'Islam',
                'role'        => 'masteradmin',
                'password'    => Hash::make('12345678'),
                'is_active'   => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
