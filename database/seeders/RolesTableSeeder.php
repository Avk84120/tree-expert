<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'description' => 'Full access to manage system',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'officer',
                'description' => 'Can manage projects and tree surveys',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'employee',
                'description' => 'Can add and update assigned trees/plantations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
