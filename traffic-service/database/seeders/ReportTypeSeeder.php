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
                    'name'  => 'accident',
                    'color'  => null,
                    'description' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ),

                array(
                    'id' => 2,
                    'name'  => 'embouteillage',
                    'color'  => null,
                    'description' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ),

                array(
                    'id' => 3,
                    'name'  => 'route barrée',
                    'color'  => null,
                    'description' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ),
                array(
                    'id' => 4,
                    'name'  => 'danger',
                    'color'  => null,
                    'description' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ),
           
        ));
    }
}
