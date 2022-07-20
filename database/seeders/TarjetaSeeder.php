<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class TarjetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tarjetas = [
            ['nombre' => 'VISA'],
            ['nombre' => 'MASTERCARD'],
            ['nombre' => 'MAESTRO'],
            ['nombre' => 'NARANJA'],
            ['nombre' => 'CABAL'],
        ];

        DB::table('tarjetas')->insert($tarjetas);
    }
}
