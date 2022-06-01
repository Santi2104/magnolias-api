<?php

use App\Http\Controllers\Api\Admin\AdministrativoController;
use App\Http\Controllers\Api\Admin\AfiliadoController;
use App\Http\Controllers\Api\Admin\BarrioController;
use App\Http\Controllers\Api\Admin\CalleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\CoordinadorController;
use App\Http\Controllers\Api\Admin\DepartamentoController;
use App\Http\Controllers\Api\Admin\LocalidadController;
use App\Http\Controllers\Api\Admin\ObraSocialController;
use App\Http\Controllers\Api\Admin\PaisController;
use App\Http\Controllers\Api\Admin\PaqueteController;
use App\Http\Controllers\Api\Admin\PaqueteProductoController;
use App\Http\Controllers\Api\Admin\ProductoController;
use App\Http\Controllers\Api\Admin\ProvinciaController;
use App\Http\Controllers\Api\Admin\VendedorController;

Route::group(['middleware' => ['auth:api','scope:*','checkaccept']], function(){

    Route::group([
        "as" => 'admin.'
    ], function() {

        //**Crud de los productos */
        Route::get('productos', [ProductoController::class, 'index'])->name('productos.index');
        Route::get('producto/{id}', [ProductoController::class, 'show'])->name('productos.show');
        Route::post('producto', [ProductoController::class, 'store'])->name('productos.store');//*TODO: Capturar para esta ruta el Illuminate\\Database\\QueryException
        Route::put('producto/{id}', [ProductoController::class, 'update'])->name('productos.update');
        Route::delete('producto/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');
        Route::patch('producto/{id}', [ProductoController::class, 'restore'])->name('productos.restore');

        //**Curd de los paquetes */
        Route::get('paquetes', [PaqueteController::class, 'index'])->name('paquete.index');
        Route::post('paquete', [PaqueteController::class, 'store'])->name('paquete.store');
        Route::put('paquete/{id}', [PaqueteController::class, 'update'])->name('paquete.update');
        Route::delete('paquete/{id}', [PaqueteController::class, 'destroy'])->name('paquete.destroy');
        Route::patch('paquete/{id}', [PaqueteController::class, 'restore'])->name('paquete.restore');
        Route::get('paquete/producto', [PaqueteProductoController::class, 'index'])->name('paquete.producto.index');
        Route::get('paquete/{id}/producto', [PaqueteProductoController::class, 'show'])->name('paquete.producto.show');
        Route::post('paquete/producto', [PaqueteProductoController::class, 'store'])->name('paquete.producto.store');
        Route::put('paquete/{id}/producto', [PaqueteProductoController::class, 'update'])->name('paquete.producto.update');
        Route::delete('paquete/{id}/producto', [PaqueteProductoController::class, 'destroy'])->name('paquete.producto.destroy');
        
        //**Crud de las localidades */
        Route::get('localidades', [LocalidadController::class, 'index'])->name('localidad.index');
        Route::get('localidad/{id}', [LocalidadController::class, 'show'])->name('localidad.show');
        Route::post('localidad', [LocalidadController::class, 'store'])->name('localidad.store');
        Route::put('localidad/{id}', [LocalidadController::class, 'update'])->name('localidad.update');
        Route::delete('localidad/{id}', [LocalidadController::class, 'destroy'])->name('localidad.destroy');
        Route::patch('localidad/{id}', [LocalidadController::class, 'restore'])->name('localidad.restore');

        //**Crud de las obras sociales */
        Route::get('obra-social', [ObraSocialController::class, 'index'])->name("obra.social.index");
        Route::post('obra-social', [ObraSocialController::class, 'store'])->name("obra.social.store");
        Route::put('obra-social/{id}', [ObraSocialController::class, 'update'])->name("obra.social.update");
        Route::delete('obra-social/{id}', [ObraSocialController::class, 'destroy'])->name("obra.social.destroy");
        Route::patch('obra-social/{id}', [ObraSocialController::class, 'restore'])->name("obra.social.restore");

        //**Crud de los coordinadores */
        Route::get('coordinadores', [CoordinadorController::class, 'index'])->name("coordinador.index");
        Route::get('coordinador/vendedor', [CoordinadorController::class,'show'])->name("coordinador.vendedor.index");
        Route::post('coordinador', [CoordinadorController::class, 'store'])->name("coordinador.store");
        Route::put('coordinador', [CoordinadorController::class, 'update'])->name("coordinador.update");

        //**Curd de los vendedores */
        Route::get('vendedores', [VendedorController::class, 'index'])->name('vendedor.index');
        Route::get('vendedor',[VendedorController::class, 'show'])->name('vendedor.show');
        Route::post('vendedor', [VendedorController::class, 'store'])->name('vendedor.store');
        Route::put('vendedor', [VendedorController::class, 'update'])->name('vendedor.update');

        //**Crud de los administrativos */
        Route::get('administrativo', [AdministrativoController::class, 'index'])->name("administrativo.index");
        Route::post('administrativo', [AdministrativoController::class, 'store'])->name("administrativo.store");
        Route::put('administrativo', [AdministrativoController::class, 'update'])->name("administrativo.update");
        Route::delete('administrativo', [AdministrativoController::class, 'destroy'])->name("administrativo.destroy");

        //**Crud de los afiliados */
        Route::get('afiliados', [AfiliadoController::class,'index'])->name("afiliados.index");
        Route::post('afiliado', [AfiliadoController::class, 'store'])->name('afiliado.store');
        Route::get('afiliado', [AfiliadoController::class, 'show'])->name('afiliado.show');
        Route::put('afiliado/solicitante', [AfiliadoController::class, 'updateSolicitante'])->name('afiliado.solicitante');
        Route::put('afiliado/familia/edit', [AfiliadoController::class, 'actualizarFamiliar'])->name('afiliado.familiar');
        Route::get('afiliado/datos',[AfiliadoController::class,'datosAfiliado'])->name('afiliado.datos');
        Route::get('afiliado/familia',[AfiliadoController::class,'familiaresDelAfiliado'])->name('afiliado.familia');
        Route::delete('afiliado/solicitante',[AfiliadoController::class,'bajaSolicitante'])->name('afiliado.baja_solicitante');
        Route::post('afiliado/familia',[AfiliadoController::class,'pasarFamiliarASolicitante'])->name('afiliado.familiar_update');

        //**Crud de los paises */
        Route::get('paises',[PaisController::class,'index'])->name('pais.index');
        Route::post('pais',[PaisController::class,'store'])->name('pais.store');
        Route::put('pais',[PaisController::class,'update'])->name('pais.update');

        //**Crud de las provincias */
        Route::get('provincias', [ProvinciaController::class,'index'])->name('provincia.index');
        Route::get('provincia/{id}', [ProvinciaController::class,'show'])->name('provincia.show');
        Route::post('provincia', [ProvinciaController::class,'store'])->name('provincia.store');
        Route::put('provincia', [ProvinciaController::class,'update'])->name('provincia.update');

        //**Crud de los departamentos */
        Route::get('departamentos', [DepartamentoController::class,'index'])->name('departamento.index');
        Route::get('departamento/{id}', [DepartamentoController::class,'show'])->name('departamento.show');
        Route::post('departamento', [DepartamentoController::class,'store'])->name('departamento.store');
        Route::put('departamento', [DepartamentoController::class,'update'])->name('departamento.update');
        
        //**Crud de los barrios */
        Route::get('barrios', [BarrioController::class, 'index'])->name('barrio.index');
        Route::post('barrio', [BarrioController::class, 'store'])->name('barrio.store');
        Route::put('barrio', [BarrioController::class, 'update'])->name('barrio.update');

        //**Crud de las calles */
        Route::get('calles', [CalleController::class, 'index'])->name('calle.index');
        Route::post('calle', [CalleController::class, 'store'])->name('calle.store');
        Route::put('calle', [CalleController::class, 'update'])->name('calle.update');
        
        
    });
});