<?php

namespace Database\Seeders;

use App\Models\Afiliado;
use App\Models\Categoria;
use App\Models\Coordinador;
use App\Models\Localidad;
use App\Models\ObraSocial;
use App\Models\Paquete;
use App\Models\Producto;
use App\Models\Vendedor;
use App\Models\Zona;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::factory()
        ->count(1)
        ->create([
            'name' => 'admin',
            //'display_name' => 'Administrador'
        ]);
        \App\Models\Role::factory()
        ->count(1)
        ->create([
            'name' => 'coordinador',
            //'display_name' => 'Paciente'
        ]);
        \App\Models\Role::factory()
        ->count(1)
        ->create([
            'name' => 'afiliado',
            //'display_name' => 'Profesional'
        ]);

        \App\Models\Role::factory()
        ->count(1)
        ->create([
            'name' => 'vendedor',
            //'display_name' => 'Admisión'
        ]);

        ObraSocial::factory()->times(1)->create([
            'nombre' => 'APOS',
            // 'razon_social' => 'APOS',
            // 'nombre_comercial' => 'APOS',
        ]);

        ObraSocial::factory()->times(1)->create([
            'nombre' => 'OSUNLAR',
            // 'razon_social' => 'OSUNLAR',
            // 'nombre_comercial' => 'OSUNLAR',
        ]);

        ObraSocial::factory()->times(1)->create([
            'nombre' => 'OSDE',
            // 'razon_social' => 'OSDE',
            // 'nombre_comercial' => 'OSDE',
        ]);

        \App\Models\User::factory()
        ->count(1)
        ->create(['name' => 'Sergio','lastname' => 'Denis','email' => 'sergio@mail.com','role_id' => 1]);

        \App\Models\User::factory()
        ->count(1)
        ->create(['name' => 'Coordinador','lastname' => 'Coordinador','email' => 'coordinador@mail.com','role_id' => 2])
        ->each(function (\App\Models\User $user){
            Coordinador::factory()
            ->create(['user_id' => $user->id]);
        });

        \App\Models\User::factory()
        ->count(5)
        ->create(['role_id' => 2])
        ->each(function (\App\Models\User $user){
            Coordinador::factory()
            ->create(['user_id' => $user->id]);
        });
     
        Producto::factory()
        ->count(4)
        ->has(Categoria::factory()->count(1))
        ->create();
        
        Paquete::factory()
        ->count(7)
        ->has(Producto::factory()
                    ->count(3))
                    ->create();

        Zona::factory()
        ->count(10)
        ->has(Localidad::factory()
                    ->count(1))
        ->create();

        \App\Models\User::factory()
        ->count(20)
        ->create(['role_id' => 3])
        ->each(function (\App\Models\User $user){
            Afiliado::factory()
            ->create(['user_id' => $user->id, 'obra_social_id' => rand(1,3)]);
        });


        \App\Models\User::factory()
        ->count(10)
        ->create(['role_id' => 4])
        ->each(function (\App\Models\User $user){
            Vendedor::factory()
            ->create(['user_id' => $user->id, 'coordinador_id' => rand(1,4)]);
        });

        for ($i=1; $i <= 17; $i++) { 
            $afiliado = Afiliado::find($i);
            $afiliado->vendedores()->attach(rand(1,8)); 
        }


    }
}
