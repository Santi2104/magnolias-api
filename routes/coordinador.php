<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Coordinador\VendedoresController;

Route::group(['middleware' => ['auth:api','scope:coordinador','checkaccept']], function(){

    Route::group([
        "as" => 'coordinador.'
    ], function(){

        //!No puede ni crear, ni editar un vendedor
        Route::get('vendedores', [VendedoresController::class, 'index'])->name('vendedor.index');
        Route::get('vendedor/{uuid}/afiliados', [VendedoresController::class, 'show'])->name('vendedor.afiliado.show');
        Route::post('vendedor', [VendedoresController::class, 'store'])->name('vendedor.store');
        Route::put('vendedor/{uuid}', [VendedoresController::class, 'update'])->name('vendedor.afiliado.update');
        Route::delete('vendedor', [VendedoresController::class, 'uncouple'])->name('vendedor.uncouple');
        Route::post('vendedor/{id}/zonas', [VendedoresController::class, 'vendedorZonas'])->name('vendedor.zonas');

    });


});