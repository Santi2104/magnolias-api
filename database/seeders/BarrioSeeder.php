<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class BarrioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('barrios')->insert([
            ['id' => 15,'nbarrio' => 'CENTRO ', 'localidad_id' => 134,],
            ['id' => 16,'nbarrio' => 'SAN MARTIN ', 'localidad_id' => 134,],
            ['id' => 17,'nbarrio' => 'ARGENTINO ', 'localidad_id' => 134,],
            ['id' => 18,'nbarrio' => 'MATADERO ', 'localidad_id' => 134,],
            ['id' => 19,'nbarrio' => 'CARDONAL ', 'localidad_id' => 134,],
            ['id' => 20,'nbarrio' => 'HOSPITAL ', 'localidad_id' => 134,],
            ['id' => 21,'nbarrio' => 'VARGAS ', 'localidad_id' => 134,],
            ['id' => 22,'nbarrio' => 'BENJAMIN RINCON ', 'localidad_id' => 134,],
            ['id' => 23,'nbarrio' => 'FACUNDO QUIROGA ', 'localidad_id' => 134,],
            ['id' => 24,'nbarrio' => 'ANTARTIDA I ', 'localidad_id' => 134,],
            ['id' => 25,'nbarrio' => 'NUEVA ARGENTINA ', 'localidad_id' => 134,],
            ['id' => 27,'nbarrio' => 'SAN CAYETANO ', 'localidad_id' => 134,],
            ['id' => 28,'nbarrio' => 'ANTARTIDA II ', 'localidad_id' => 134,],
            ['id' => 29,'nbarrio' => 'FERROVIARIO ', 'localidad_id' => 134,],
            ['id' => 30,'nbarrio' => 'SHINCAL ', 'localidad_id' => 134,],
            ['id' => 31,'nbarrio' => 'LA HERMITA ', 'localidad_id' => 134,],
            ['id' => 32,'nbarrio' => 'PARQUE SUR ', 'localidad_id' => 134,],
            ['id' => 33,'nbarrio' => 'COCHANGASTA', 'localidad_id' => 134,],
            ['id' => 34,'nbarrio' => 'EVITA ', 'localidad_id' => 134,],
            ['id' => 35,'nbarrio' => 'ISLAS MALVINAS ', 'localidad_id' => 134,],
            ['id' => 36,'nbarrio' => 'SAN VICENTE ', 'localidad_id' => 134,],
            ['id' => 37,'nbarrio' => 'INFANTERIA II ', 'localidad_id' => 134,],
            ['id' => 38,'nbarrio' => 'LAS BREAS ', 'localidad_id' => 134,],
            ['id' => 39,'nbarrio' => 'CEMENTERIO ', 'localidad_id' => 134,],
            ['id' => 40,'nbarrio' => 'LOS OLIVARES ', 'localidad_id' => 134,],
            ['id' => 41,'nbarrio' => 'LA HERMITILLA ', 'localidad_id' => 134,],
            ['id' => 42,'nbarrio' => 'SIN ESPECIFICAR','localicad_id' => 26,],
            ['id' => 43,'nbarrio' => 'FEDERACION I ', 'localidad_id' => 134,],
            ['id' => 44,'nbarrio' => 'FALDEO DEL VELAZCO SUR ', 'localidad_id' => 134,],
            ['id' => 45,'nbarrio' => '88 VIVIENDAS ', 'localidad_id' => 134,],
            ['id' => 46,'nbarrio' => 'PARQUE INDUSTRIAL ', 'localidad_id' => 134,],
            ['id' => 47,'nbarrio' => 'SAN ROMAN ', 'localidad_id' => 134,],
            ['id' => 48,'nbarrio' => 'MERCANTIL ', 'localidad_id' => 134,],
            ['id' => 49,'nbarrio' => 'LOS FILTROS ', 'localidad_id' => 134,],
            ['id' => 50,'nbarrio' => 'TAMBOR DE TACUARI ', 'localidad_id' => 134,],
            ['id' => 51,'nbarrio' => 'LA QUEBRADA ', 'localidad_id' => 134,],
            ['id' => 52,'nbarrio' => 'PANAMERICANO ', 'localidad_id' => 134,],
            ['id' => 53,'nbarrio' => '9 DE JULIO 55', 'localidad_id' => 134,],
            ['id' => 54,'nbarrio' => 'U.P.C.N ', 'localidad_id' => 134,],
            ['id' => 55,'nbarrio' => 'JARDIN RESIDENCIAL ', 'localidad_id' => 134,],
            ['id' => 56,'nbarrio' => 'EL CARDONAL ', 'localidad_id' => 134,],
            ['id' => 57,'nbarrio' => 'ALTA RIOJA ', 'localidad_id' => 134,],
            ['id' => 58,'nbarrio' => 'SIN ESPECIFICAR', 'localidad_id' => 134,],
            ['id' => 59,'nbarrio' => 'CENTRO ','localidad_id' => 26,],
            ['id' => 60,'nbarrio' => 'SIN ESPECIFICAR','localidad_id' => 24,],
            ['id' => 61,'nbarrio' => 'SAN CLEMENTE ', 'localidad_id' => 134,],
            ['id' => 62,'nbarrio' => 'JOAQUIN VICTOR GONZALEZ ', 'localidad_id' => 134,],
            //(63, 'LA PUNILLA ', 664, 'S'),
            ['id' => 64,'nbarrio' => 'RAMIREZ DE VELAZCO ', 'localidad_id' => 134,],
            ['id' => 65,'nbarrio' => 'TIRO FEDERAL ', 'localidad_id' => 134,],
        ]);
    }
}
