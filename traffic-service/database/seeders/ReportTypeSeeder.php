<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportTypeSeeder extends Seeder
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
                    'name'  => 'type 1',
                    'color'  => null,
                    'description' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ),

                array(
                    'id' => 2,
                    'name'  => 'type 2',
                    'color'  => null,
                    'description' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ),

                array(
                    'id' => 3,
                    'name'  => 'type 3',
                    'color'  => null,
                    'description' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ),
           
        ));
    }
}
