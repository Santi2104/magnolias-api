<?php

use App\Http\Controllers\Api\Administrativo\CategoriaController;
use App\Http\Controllers\Api\Administrativo\CoordinadorController;
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
    });
});