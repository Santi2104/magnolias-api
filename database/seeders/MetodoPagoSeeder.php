<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $metodosPago = [
            ['nombre' => 'Contado','codigo' => '1' ],
            ['nombre' => 'Efectivo','codigo' => 'ef' ],
            ['nombre' => 'Sin Cargo','codigo' => 'S/C' ],
            ['nombre' => 'Tarjerta de Credito','codigo' => 'TC' ],
            ['nombre' => 'Tarjerta de Debito','codigo' => 'TD' ],
            ['nombre' => 'Pagare','codigo' => 'Pag' ],
            ['nombre' => 'Descuento por planilla','codigo' => 'DPP' ],
        ];

        DB::table('metodo_pagos')->insert($metodosPago);
    }
}
