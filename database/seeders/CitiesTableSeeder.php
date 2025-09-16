<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('cities')->insert([
            ['name' => 'Mumbai', 'state_id' => 1],
            ['name' => 'Pune', 'state_id' => 1],
            ['name' => 'Ahmedabad', 'state_id' => 2],
            ['name' => 'Surat', 'state_id' => 2],
            ['name' => 'Bangalore', 'state_id' => 3],
            // Add more cities as needed
        ]);
    }
}