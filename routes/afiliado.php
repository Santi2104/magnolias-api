<?php

use App\Http\Controllers\Api\Afiliado\PaqueteController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:api','scope:afiliado','checkaccept']], function(){

    Route::group([
        "as" => "afiliado."
    ], function(){

        Route::get('paquete', [PaqueteController::class,'index'])->name('paquete.index');
    });


});