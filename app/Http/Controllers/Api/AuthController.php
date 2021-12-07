<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    use ApiHelpers;

    public function login(Request $request)
    {

        if($this->checkHeaders($request->header('Content-Type'),$request)){
            return $this->onError(422,'Error en las cabeceras');
        }

        $request->validate([ 
            'email'       => ['required','string','email'],
            'password'    => ['required','string'],
            'remember_me' => ['boolean'],
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            //*TODO: Cambiar este mensaje que no nos dice nada
            return $this->onError(401,'Unauthorized');    
        }

        $user = $request->user();
        
        $userRole = $user->UserRole();

        switch ($userRole) {
            case 1:
                    $tokenResult = $user->createToken('Personal Access Token', [implode(" ", Role::ADMIN_TOKEN)]);
                break;
            
            case 2:
                    $tokenResult = $user->createToken('Personal Access Token', [implode(" ",Role::COORDINADOR_TOKEN)]);
                    break;

            case 4:
                    $tokenResult = $user->createToken('Personal Access Token', [implode(" ",Role::VENDEDOR_TOKEN)]);
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
            "Inicio de sesión satisfactorio",
            $user
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

    public function register(Request $request)
    {

        if($this->checkHeaders($request->header('Content-Type'),$request)){
            return $this->onError(422,'Error en las cabeceras');
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

        return $this->onMessage(201,"Usuario creado de manera satisfactoria");    
    }
}
