<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProduccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'admin' , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'coordinador' , 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'afiliado', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'vendedor', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'administrativo', 'created_at' => now(), 'updated_at' => now()]
        ]);

        DB::table('obra_sociales')->insert([
            ['nombre' => 'APOS', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'OSUNLAR', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'OSDE', 'created_at' => now(), 'updated_at' => now()]
        ]);

        DB::table('users')->insert([
            [
                'name' => 'Santiago',
                'lastname' => 'Ortiz Ocampo',
                'dni' => '12345678',
                'edad' => '28',
                'nacimiento' => '1993-04-21',
                'email' => 'admin@mail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'role_id' => Role::ES_ADMIN,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'Emiliano',
                'lastname' => 'Romero',
                'dni' => '12345679',
                'edad' => '27',
                'nacimiento' => '1994-04-04',
                'email' => 'coordinador@mail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'role_id' => Role::ES_COORDINADOR,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'Sergio',
                'lastname' => 'Denis',
                'dni' => '12345680',
                'edad' => '27',
                'nacimiento' => '1994-04-04',
                'email' => 'vendedor@mail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'role_id' => Role::ES_VENDEDOR,
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'Juan Carlos',
                'lastname' => 'bodoque',
                'dni' => '12345681',
                'edad' => '27',
                'nacimiento' => '1994-04-04',
                'email' => 'administrativo@mail.com',
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'role_id' => Role::ES_ADMINISTRATIVO,
                'created_at' => now(),
                'updated_at' => now(),

            ]
            ]);

            DB::table('coordinadores')->insert([
                [
                    'codigo_coordinador' => Str::uuid(),
                    'user_id' => 2
                ]
                ]);

            DB::table('vendedores')->insert([
                [
                    'user_id' => 3,
                    'codigo_vendedor' => Str::uuid(),
                    'coordinador_id' => 1
                ]
                ]);
            DB::table('administrativos')->insert([
                [
                    'user_id' => 4,
                    'codigo_administrativo' => Str::uuid(),
                ]
                ]);

            DB::table('productos')->insert([
                [
                    'nombre' => 'Responso',
                ],
                [
                    'nombre' => 'Cremación ', 
                ],
                [
                    'nombre' => 'Entrega de urna',
                ]
                ]);

            DB::table('paquetes')->insert([
                [
                    "nombre" => 'PLAN INDIVIDUAL',
                    "precio" => 1500
                ],
                [
                    "nombre" => 'PLAN MAYOR',
                    "precio" => 2000
                ],
                [
                    "nombre" => 'PLAN FAMILIA',
                    "precio" => 2300
                ]
                ]);

            DB::table('paquete_producto')->insert([
                [
                    'paquete_id' => 1,
                    'producto_id' => 1
                ],
                [
                    'paquete_id' => 1,
                    'producto_id' => 2
                ],
                [
                    'paquete_id' => 1,
                    'producto_id' => 3
                ],
                [
                    'paquete_id' => 2,
                    'producto_id' => 1,
                ],
                [
                    'paquete_id' => 2,
                    'producto_id' => 2,
                ],
                [
                    'paquete_id' => 2,
                    'producto_id' => 3,
                ],
                [
                    'paquete_id' => 3,
                    'producto_id' => 1
                ],
                [
                    'paquete_id' => 3,
                    'producto_id' => 2
                ],
                [
                    'paquete_id' => 3,
                    'producto_id' => 3
                ],
                ]);
    }
}
