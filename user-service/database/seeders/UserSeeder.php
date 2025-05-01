<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('report_types')->truncate();


        DB::table('report_types')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'name' => 'first',
                    'email' => 'test@example.com',
                    'password' => bcrypt('password'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ),
        ));
    }
}
