<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('states')->insert([
            ['name' => 'Maharashtra'],
            ['name' => 'Gujarat'],
            ['name' => 'Karnataka'],
            // Add more states as needed
        ]);
    }
}