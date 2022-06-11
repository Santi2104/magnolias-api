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
            'dni' => ['required', Rule::unique(User::class)],
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

    public function user(Request $request)
    {
        return new UserAuthResource($request->user());
    }

    public function reiniciarCuenta(Request $request)
    {

        $usuario = User::where('id',$request['id'])->first();

        if(!$usuario)
        {
           return $this->onError(422,"Error al reiniciar cuenta","No se encuentra el usuario con ese ID");
        }

        if(!$usuario->reset_email)
        {
            return $this->onError(422,"Error al reiniciar cuenta","Esta cuenta no tiene pedido de reinicio");
        }


        $validador = Validator::make($request->all(), [
            'id' => ['required','exists:App\Models\User,id'],
            'username' => ['required','string'],
            'password' => ['required','string'],
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $usuario->username = $request['username'];
        $usuario->password = bcrypt($request['password']);
        $usuario->reset_email = false;

        $usuario->save();

        return $this->onSuccess($usuario,"Cuanta reestablecida de manera correcta");
    }
}
