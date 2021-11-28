<?php

use App\Http\Controllers\Api\Admin\CategoriasController;
use App\Http\Controllers\Api\Admin\LocalidadController;
use App\Http\Controllers\Api\Admin\PaqueteController;
use App\Http\Controllers\Api\Admin\ProductoController;
use App\Http\Controllers\Api\Admin\ZonaController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register',[AuthController::class, 'register']);
  
    Route::group(['middleware' => 'auth:api'], function() {

        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

Route::group(['middleware' => ['auth:api','checkaccept']], function(){

    Route::group([
        "prefix" => "admin",
        "as" => 'admin.'
    ], function() {
        //**Crud de las categorias */
        Route::get('categorias', [CategoriasController::class, 'index'])->name('categorias.index');
        Route::post('categoria', [CategoriasController::class, 'store'])->name('categorias.store');
        Route::put('categoria/{categoria}', [CategoriasController::class, 'update'])->name('categorias.update');

        //**Crud de los productos */
        Route::get('productos', [ProductoController::class, 'index'])->name('productos.index');
        Route::get('producto/{id}', [ProductoController::class, 'show'])->name('productos.show');
        Route::post('producto', [ProductoController::class, 'store'])->name('productos.store');
        Route::put('producto/{id}', [ProductoController::class, 'update'])->name('productos.update');

        //**Curd de los paquetes */
        Route::get('paquetes', [PaqueteController::class, 'index'])->name('paquete.index');
        Route::post('paquete', [PaqueteController::class, 'store'])->name('paquete.store');
        Route::put('paquete/{id}', [PaqueteController::class, 'update'])->name('paquete.update');

        //**Crud de las localidades */
        Route::get('localidades', [LocalidadController::class, 'index'])->name('localidad.index');
        Route::post('localidad', [LocalidadController::class, 'store'])->name('localidad.store');
        Route::put('localidad/{id}', [LocalidadController::class, 'update'])->name('localidad.update');

        //**Crud de las zonas */
        Route::get('zonas', [ZonaController::class, 'index'])->name('zona.index');
        Route::post('zona', [ZonaController::class, 'store'])->name('zona.store');
        Route::put('zona/{id}', [ZonaController::class, 'update'])->name('zona.update');
        
        
    });
});
