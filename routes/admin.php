<?php

use App\Http\Controllers\Api\Admin\AdministrativoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\CategoriasController;
use App\Http\Controllers\Api\Admin\CoordinadorController;
use App\Http\Controllers\Api\Admin\LocalidadController;
use App\Http\Controllers\Api\Admin\ObraSocialController;
use App\Http\Controllers\Api\Admin\PaqueteController;
use App\Http\Controllers\Api\Admin\PaqueteProductoController;
use App\Http\Controllers\Api\Admin\ProductoController;
use App\Http\Controllers\Api\Admin\ZonaController;

Route::group(['middleware' => ['auth:api','scope:admin','checkaccept']], function(){

    Route::group([
        "as" => 'admin.'
    ], function() {
        //**Crud de las categorias */
        Route::get('categorias', [CategoriasController::class, 'index'])->name('categorias.index');
        Route::post('categoria', [CategoriasController::class, 'store'])->name('categorias.store');
        Route::put('categoria/{categoria}', [CategoriasController::class, 'update'])->name('categorias.update');
        Route::delete('categoria/{categoria}', [CategoriasController::class, 'destroy'])->name('categorias.destroy');
        Route::patch('categoria/{id}', [CategoriasController::class, 'restore'])->name('categorias.restore');

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
        Route::post('localidad', [LocalidadController::class, 'store'])->name('localidad.store');
        Route::put('localidad/{id}', [LocalidadController::class, 'update'])->name('localidad.update');
        Route::delete('localidad/{id}', [LocalidadController::class, 'destroy'])->name('localidad.destroy');
        Route::patch('localidad/{id}', [LocalidadController::class, 'restore'])->name('localidad.restore');

        //**Crud de las zonas */
        Route::get('zonas', [ZonaController::class, 'index'])->name('zona.index');
        Route::post('zona', [ZonaController::class, 'store'])->name('zona.store');
        Route::put('zona/{id}', [ZonaController::class, 'update'])->name('zona.update');
        //*!Segun las respuestas un vendedor puede pertenecer a varias zonas, esto habra que cambiar */

        //**Crud de las obras sociales */
        Route::get('obra-social', [ObraSocialController::class, 'index'])->name("obra.social.index");
        Route::post('obra-social', [ObraSocialController::class, 'store'])->name("obra.social.store");
        Route::put('obra-social/{id}', [ObraSocialController::class, 'update'])->name("obra.social.update");
        Route::delete('obra-social/{id}', [ObraSocialController::class, 'destroy'])->name("obra.social.destroy");
        Route::patch('obra-social/{id}', [ObraSocialController::class, 'restore'])->name("obra.social.restore");

        //**Crud de los coordinadores */
        Route::get('coordinadores', [CoordinadorController::class, 'index'])->name("coordinador.index");
        Route::post('coordinador', [CoordinadorController::class, 'store'])->name("coordinador.store");
        Route::put('coordinador', [CoordinadorController::class, 'update'])->name("coordinador.update");

        //**Crud de los administrativos */
        Route::get('administrativo', [AdministrativoController::class, 'index'])->name("administrativo.index");
        Route::post('administrativo', [AdministrativoController::class, 'store'])->name("administrativo.store");
        Route::put('administrativo', [AdministrativoController::class, 'update'])->name("administrativo.update");
        
    });
});