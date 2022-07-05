<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('paises')->insert([
            ['npais' => 'ARGENTINA'],
            ['npais' => 'URUGUAY'],
            ['npais' => 'BRASIL'],
            ['npais' => 'CHILE'],
            ['npais' => 'BOLIVIA'],
            ['npais' => 'VENEZUELA'],
            ['npais' => 'ECUADOR'],
            ['npais' => 'PARAGUAY'],
            ['npais' => 'MEXICO'],
            ['npais' => 'EEUU'],
            ['npais' => 'PERU'],
            ['npais' => 'COLOMBIA'],
            ['npais' => 'COREA'],
            ['npais' => 'CHINA']
        ]);
    }
}
