<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('calles')->insert([
            ['id' => 13,'ncalle' => 'PELAGIO B. LUNA ', 'localidad_id' => 134,],
            ['id' => 14,'ncalle' => 'SAN NICOLAS DE BARI ', 'localidad_id' => 134,],
            ['id' => 15,'ncalle' => 'CATAMARCA ', 'localidad_id' => 134,],
            ['id' => 16,'ncalle' => 'RIVADAVIA ', 'localidad_id' => 134,],
            ['id' => 17,'ncalle' => 'BUENOS AIRES ', 'localidad_id' => 134,],
            ['id' => 18,'ncalle' => 'LAMADRID ', 'localidad_id' => 134,],
            ['id' => 19,'ncalle' => '9 DE JULIO ', 'localidad_id' => 134,],
            ['id' => 20,'ncalle' => 'SAN MARTIN ', 'localidad_id' => 134,],
            ['id' => 21,'ncalle' => 'AV. PERON', 'localidad_id' => 134,],
            ['id' => 22,'ncalle' => 'COPIAPO ', 'localidad_id' => 134,],
            ['id' => 23,'ncalle' => 'JUJUY ', 'localidad_id' => 134,],
            ['id' => 24,'ncalle' => 'AYAN OESTE','localidad_id' =>  24,],
            ['id' => 25,'ncalle' => 'DALMACIO VELEZ SARFIELD ', 'localidad_id' => 134,],
            ['id' => 26,'ncalle' => 'RIO NEGRO ', 'localidad_id' => 134,],
            ['id' => 27,'ncalle' => 'JUSTO JOSE DE URQUIZA ', 'localidad_id' => 134,],
            ['id' => 28,'ncalle' => 'RAMIREZ DE VELAZCO', 'localidad_id' => 134,],
            ['id' => 29,'ncalle' => 'EL CHACHO ', 'localidad_id' => 134,],
            ['id' => 30,'ncalle' => 'CHARRUAS ', 'localidad_id' => 134,],
            ['id' => 31,'ncalle' => 'FACUNDO QUIROGA ', 'localidad_id' => 134,],
            ['id' => 32,'ncalle' => 'SAN ISIDRO ', 'localidad_id' => 134,],
            ['id' => 33,'ncalle' => 'FRANCISCO SOLANO GOMEZ ', 'localidad_id' => 134,],
            ['id' => 34,'ncalle' => 'INDEPENDENCIA ', 'localidad_id' => 134,],
            ['id' => 35,'ncalle' => 'ISLA DECEPCION ', 'localidad_id' => 134,],
            ['id' => 36,'ncalle' => 'JOAQUIN VICTOR GONZALEZ ', 'localidad_id' => 134,],
        ]);
    }
}
