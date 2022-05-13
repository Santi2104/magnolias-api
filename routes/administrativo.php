<?php

use App\Http\Controllers\Api\Administrativo\AfiliadoController;
use App\Http\Controllers\Api\Administrativo\CategoriaController;
use App\Http\Controllers\Api\Administrativo\CoordinadorController;
use App\Http\Controllers\Api\Administrativo\PagoController;
use App\Http\Controllers\Api\Administrativo\PaqueteController;
use App\Http\Controllers\Api\Administrativo\UbicacionesController;
use App\Http\Controllers\Api\Administrativo\VendedorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api','scope:administrativo','checkaccept']], function(){
    Route::group(['as' => 'administrativo.'], function(){

        Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index');

        //**Rutas para manejo de Coordinadores */
        Route::get('coordinadores',[CoordinadorController::class, 'index'])->name('coordinador.index');
        Route::post('coordinador', [CoordinadorController::class, 'store'])->name('coordinador.store');
        Route::put('coordinador',[CoordinadorController::class, 'update'])->name('coordinador.update');

        //**Rutas para el manejo de los Vendedores */
        Route::get('vendedores', [VendedorController::class, 'index'])->name('vendedor,index');
        Route::post('vendedor', [VendedorController::class, 'store'])->name('vendedor.store');
        Route::put('vendedor',[VendedorController::class, 'update'])->name('vendedor.update');

        //**Rutas para los afiliados */
        Route::get('afiliados', [AfiliadoController::class, 'index'])->name('afiliado,index');
        Route::get('afiliado', [AfiliadoController::class, 'show'])->name('afiliado.show');
        Route::get('afiliado/datos',[AfiliadoController::class,'datosAfiliado'])->name('afiliado.datos');
        Route::get('afiliado/familia',[AfiliadoController::class,'familiaresDelAfiliado'])->name('afiliado.familia');
        Route::post('afiliado', [AfiliadoController::class, 'store'])->name('afiliado.store');
        Route::put('afiliado/solicitante', [AfiliadoController::class, 'updateSolicitante'])->name('afiliado.solicitante');
        Route::put('afiliado/familia/edit', [AfiliadoController::class, 'actualizarFamiliar'])->name('afiliado.familiar');

        //**Rutas para los paquetes */
        Route::get('paquetes', [PaqueteController::class, 'index'])->name('paquete.index');

        //**Rutas para las ubicaciones */
        Route::get('pais/{id}/provincias', [UbicacionesController::class, 'listarProvinciasPorPais'])->name('pais.provincias');
        Route::get('provincia/{id}/departamentos', [UbicacionesController::class, 'listarDepartamentosPorProvincia'])->name('provincia.departamentos');
        Route::get('departamento/{id}/localidades', [UbicacionesController::class, 'listarLocalidadesPorDepartamento'])->name('departamento.localidades');
        Route::get('localidad/{id}/calles', [UbicacionesController::class, 'listarCallesPorLocalidad'])->name('localidad.calles');
        Route::get('localidad/{id}/barrios', [UbicacionesController::class, 'listarBarriosPorLocalidad'])->name('localidad.barrios');

        //**Rutas para los pagos */
        Route::get('pagos', [PagoController::class, 'index'])->name('pagos.index');
        Route::put('pago', [PagoController::class, 'update'])->name('pagos.update');
        Route::post('pago', [PagoController::class, 'store'])->name('pagos.store');
    });
});
