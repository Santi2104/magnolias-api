<?php

namespace App\Http\Controllers\Api\Afiliado;

use App\Models\User;
use App\Models\Afiliado;
use Illuminate\Http\Request;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AfiliadoController extends Controller
{
    use ApiHelpers;
    /**
     * Listado de los datos basicos del afiliado, junto con sus pagos.
     *
     * @return \Illuminate\Http\Response
     */
    public function obtenerDatosDelAfiliado()
    {
        $afiliado = Afiliado::with([
            'paquete' => function($query){
                $query->select('id', 'nombre');
            },
            'user' => function($query){
                $query->select('id','name', 'lastname','email', 'dni');
            },
            'pagos'
        ])->get();

        return $this->onSuccess($afiliado);
    }

    public function cambiarContraseña(Request $request)
    {

        $validador = Validator::make($request->all(),[
            'username'       => ['required','string','exists:App\Models\User,username'],
            'password'    => ['required','string'],
            'new_password' => ['required', 'string']
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $user = User::where('username',$request['username'])->first();

        $contraseña = Hash::check($request['password'],$user->password);

        if(!$contraseña)
        {
            return $this->onError(422,"Error en la contraseña",'La contraseña antigua es invalida');
        }

        $user->password = bcrypt($request['new_password']);
        $user->save();

        return $this->onMessage(200,"Contraseña actualizada correctamente");
    }
}
