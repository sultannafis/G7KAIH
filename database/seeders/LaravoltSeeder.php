<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;


class LaravoltSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Artisan::call('laravolt:indonesia:seed');
    }
}
