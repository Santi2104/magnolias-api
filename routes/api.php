<?php

use App\Http\Controllers\Api\Admin\CategoriasController;
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

        Route::get('categorias', [CategoriasController::class, 'index'])->name('categorias.index');
        Route::post('categoria', [CategoriasController::class, 'store'])->name('categorias.store');
        Route::put('categoria/{categoria}', [CategoriasController::class, 'update'])->name('categorias.update');
    });
});
