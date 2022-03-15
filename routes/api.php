<?php


use App\Http\Controllers\Api\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'auth'], function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register',[AuthController::class, 'register']);
  
    Route::group(['middleware' => 'auth:api'], function() {

        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

//*TODO:Dar la posibilidad al admin de crear un administrativo
//*TODO:Agregar scopes a cada rol (Administracion, Administrativo)
//*TODO:Intentar implementar las transacciones o los eventos de Eloquent
//*TODO:Suprimir la tabla categorias que no sirve pa' nah
//*TODO:Crear una tabla para los Logs
//*TODO:Implementar un sistema de "alias" para identificar a los vendedores y coordinadores




