<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Coordinador\VendedoresController;

Route::group(['middleware' => ['auth:api','scope:coordinador','checkaccept']], function(){

    Route::group([
        "as" => 'coordinador.'
    ], function(){

        Route::get('vendedores', [VendedoresController::class, 'index'])->name('vendedor.index');
        //*?Un coordinador puede crear un vendedor? o tiene que solicitar al admin que lo cree
        //*?Un coordinador puede crear asignarse un vendedor?
        Route::get('vendedor/{uuid}/afiliados', [VendedoresController::class, 'show'])->name('vendedor.afiliado.show');
        Route::post('vendedor', [VendedoresController::class, 'store'])->name('vendedor.store');
        Route::put('vendedor/{uuid}', [VendedoresController::class, 'update'])->name('vendedor.afiliado.update');
        Route::delete('vendedor', [VendedoresController::class, 'uncouple'])->name('vendedor.uncouple');

    });


});