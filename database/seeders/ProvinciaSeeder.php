<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provincias')->insert([
            ['id' => 1,'nprovincia' => 'LA PAMPA', 'pais_id' => 1],
            ['id' => 2,'nprovincia' => 'NEUQUEN', 'pais_id' => 1],
            ['id' => 3,'nprovincia' => 'BUENOS AIRES', 'pais_id' => 1],
            ['id' => 4,'nprovincia' => 'CATAMARCA', 'pais_id' => 1],
            ['id' => 5,'nprovincia' => 'SAN LUIS', 'pais_id' => 1],
            ['id' => 6,'nprovincia' => 'CORDOBA', 'pais_id' => 1],
            ['id' => 7,'nprovincia' => 'RIO NEGRO', 'pais_id' => 1],
            ['id' => 8,'nprovincia' => 'TUCUMAN', 'pais_id' => 1],
            ['id' => 9,'nprovincia' => 'LA RIOJA', 'pais_id' => 1],
            ['id' => 10,'nprovincia' => 'CORRIENTES', 'pais_id' => 1],
            ['id' => 11,'nprovincia' => 'SANTIAGO DEL ESTERO', 'pais_id' => 1],
            ['id' => 13,'nprovincia' => 'JUJUY', 'pais_id' => 1],
            ['id' => 14,'nprovincia' => 'CHUBUT', 'pais_id' => 1],
            ['id' => 25,'nprovincia' => 'CAPITAL FEDERAL', 'pais_id' => 1],
            ['id' => 26,'nprovincia' => 'TIERRA DEL FUEGO ', 'pais_id' => 1],
            ['id' => 27,'nprovincia' => 'MISIONES ', 'pais_id' => 1],
            ['id' => 29,'nprovincia' => 'SANTA CRUZ ', 'pais_id' => 1],
            ['id' => 34,'nprovincia' => 'CHACO', 'pais_id' => 1],
            ['id' => 45,'nprovincia' => 'SALTA', 'pais_id' => 1],
            ['id' => 46,'nprovincia' => 'ENTRE RIOS', 'pais_id' => 1],
            ['id' => 47,'nprovincia' => 'SAN JUAN', 'pais_id' => 1],
            ['id' => 48,'nprovincia' => 'MENDOZA', 'pais_id' => 1],
            ['id' => 49,'nprovincia' => 'FORMOSA', 'pais_id' => 1],
            ['id' => 50,'nprovincia' => 'SANTA FE', 'pais_id' => 1]
        ]);
    }
}
