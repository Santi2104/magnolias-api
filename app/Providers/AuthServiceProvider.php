<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();

        Passport::personalAccessTokensExpireIn(now()->addDay());

        Passport::tokensCan([
            'admin' => "Privilegios de administrador",
            'coordinador' => 'Privilegios de coordinador',
            'afiliado' => 'Privilegios de afiliado',
            'vendedor' => 'Privilegios de vendedor',
            'administrativo' => 'Privilegios de administrativo',
            'categoria:index' => 'Listar Categorias',
            'categoria:store' => 'Crear Categoria',
            'categoria:update' => 'Actualizar Categorias',
            'categoria:destroy' => 'Eliminar Categoria',
            'coordinador:index' => 'Listar Coordinador',
            'coordinador:store' => 'Crear Coordinador',
            'coordinador:update' => 'Editar Coordinador',
            'coordinador:destroy' => 'Eliminar Coordinador',
            'vendedor:index' => 'Listar vendedores',
            'vendedor:store' => 'Crear vendedores',
            'vendedor:update' => 'Editar vendedores',
            'vendedor:destroy' => 'Eliminar vendedores',
        ]);

    }
}
