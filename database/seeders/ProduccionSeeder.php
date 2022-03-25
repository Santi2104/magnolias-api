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
            ['name' => 'vendedor', 'created_at' => now(), 'updated_at' => now()]
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
                'sexo' => 'M'
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
                'sexo' => 'M'
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
                'sexo' => 'M'
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
    }
}
