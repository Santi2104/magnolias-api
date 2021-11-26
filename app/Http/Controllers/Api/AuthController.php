<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        //*TODO: Mover todo lo relacionado a las respuesta dentro de un Trait 
        $content = $request->header('Content-Type');

        if(!$request->expectsJson() or $content !== "application/json" ){
            return response()->json([
                'message' => 'Error en las cabeceras'], 422);
        }

        $request->validate([ 
            'email'       => ['required','string','email'],
            'password'    => ['required','string'],
            'remember_me' => ['boolean'],
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'], 401);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {

            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'data'         => $user
        ]);
    }

    public function logout(Request $request)
    {

        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function register(Request $request)
    {

        $content = $request->header('Content-Type');

        if(!$request->expectsJson() or $content !== "application/json" ){
            return response()->json([
                'message' => 'Error en las cabeceras'], 422);
        }

        $nacimiento = Carbon::parse($request['nacimiento']);
        $actual = Carbon::now();

        //**Este login solo es para los afiliados que se registran por la pagina */
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required','string', Rule::unique(User::class)],
            'lastname' => ['required', 'string'],
            'dni' => ['required'],
            'nacimiento' => ['required'],
            'password'=> ['required','string','confirmed'],       
        ]);
        //TODO: Agregar la obra social a los usuarios que falta ese campo
        //'o_s_id' => ['required']

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'lastname' => $request->lastname,
            'dni'      => $request->dni,
            'nacimiento' => $nacimiento,
            'edad'     => $actual->diffInYears($nacimiento),
            'password' => bcrypt($request->password),
            'role_id'  => Role::ES_AFILIADO,
        ]);

        /**
         * *En este punto el usuario está sin afiliarse
         * *dentro de su panel de administracion se deberia
         * *pedirle todo el tiempo que se compre un producto
         */

        return response()->json([
            'message' => 'Successfully created user!'], 201);
    }
}
