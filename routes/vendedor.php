<?php

use App\Http\Controllers\Api\Vendedores\AfiliadosController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api','scope:vendedor','checkaccept']], function(){

    Route::group([
        "as" => 'vendedor.'
    ], function(){

        Route::get('afiliados', [AfiliadosController::class, 'index'])->name('afiliado.index');
        Route::post('afiliado', [AfiliadosController::class, 'store'])->name('afiliado.store');
        Route::get('afiliado/{uuid}', [AfiliadosController::class, 'show'])->name('afiliado.show');
        Route::put('afiliado/{uuid}', [AfiliadosController::class, 'updateAfiliado'])->name('afiliado.update');
    });


});