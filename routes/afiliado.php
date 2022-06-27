<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Afiliado\AfiliadoController;

Route::group(['middleware' => ['auth:api','scope:afiliado','checkaccept']], function(){

    Route::group([
        "as" => "afiliado."
    ], function(){

        Route::get('/', [AfiliadoController::class,'obtenerDatosDelAfiliado'])->name('afiliado.index');
        Route::post('/password', [AfiliadoController::class,'cambiarContraseña'])->name('afiliado.password');
    });


});