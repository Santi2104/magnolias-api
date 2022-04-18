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

//Agregar los cruds para los paises, provincias, etc.
//*Agregar el sistema de Logs a toda la aplicacion.
//*Agregar al administrativo la condicion de edicion antes de las 24hs.
//*Agregar el poder de editar un afiliado para el admin y el administrativo
//Terminar todos los cruds del admin, incluyendo el del grupo familiar
//*Agregar en el ApiHelper las validadiones especificas de algunos campos, como Cuil y esas cosas




