<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserAuthResource;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiHelpers;

    public function login(Request $request)
    {

        if($this->checkHeaders($request->header('Content-Type'),$request)){
            return $this->onError(422,'Error en las cabeceras');
        }

        $request->validate([ 
            'username'       => ['required','string'],
            'password'    => ['required','string'],
            'remember_me' => ['boolean'],
        ]);

        $credentials = request(['username', 'password']);

        if (!Auth::attempt($credentials)) {
            
            return $this->onError(401,'Usuario o contraseña incorrectos');  
        }

        $user = $request->user();
        
        $userRole = $user->UserRole();
        //dd(implode(", ", Role::ADMINISTRATIVO_TOKEN));
        switch ($userRole) {
            case 1:
                $tokenResult = $user->createToken('Personal Access Token', [implode(" ", Role::ADMIN_TOKEN)]);
                break;
            case 2:
                $tokenResult = $user->createToken('Personal Access Token', [implode(" ", Role::COORDINADOR_TOKEN)]);
                break;
            case 4:
                $tokenResult = $user->createToken('Personal Access Token', [implode(" ", Role::VENDEDOR_TOKEN)]);
                break;
            case 5;
                //$tokenResult = $user->createToken('Personal Access Token', [implode(" ", Role::ADMINISTRATIVO_TOKEN)]);
                $tokenResult = $user->createToken('Personal Access Token',Role::ADMINISTRATIVO_TOKEN);
                break;
            default:
                $tokenResult = $user->createToken('Personal Access Token', [implode(" ", Role::AFILIADO_TOKEN)]);
                break;
        }

        $token = $tokenResult->token;
        if ($request->remember_me) {

            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        
        $token->save();
        return $this->loginResponse(
            $tokenResult->accessToken,
            Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            $user,
            "Inicio de sesión satisfactorio",

        );
            
    }

    public function logout(Request $request)
    {
        if($this->checkHeaders($request->header('Content-Type'),$request)){
            return $this->onError(422,'Error en las cabeceras');
        }

        $request->user()->token()->revoke();
        return $this->onMessage(200, "Su sesión ha sido cerrada");
    }

    public function user(Request $request)
    {
        return new UserAuthResource($request->user());
    }

}
