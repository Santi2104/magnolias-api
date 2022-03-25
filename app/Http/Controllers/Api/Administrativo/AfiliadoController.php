<?php

namespace App\Http\Controllers\Api\Administrativo;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\Administrativo\AfiliadoResource;
use App\Models\Afiliado;
use App\Models\GrupoFamiliar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AfiliadoController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if(!Auth::user()->tokenCan('vendedor:index'))
        // {
        //     return $this->onError(403,"No está autorizado a realizar esta acción","Falta de permisos para acceder a este recurso");
        // }

        //$afiliados = Afiliado::all();
        $afiliados = User::whereRoleId(\App\Models\Role::ES_AFILIADO)->get();
        return $this->onSuccess(AfiliadoResource::collection($afiliados));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $email = null;
        $solicitante = null;
        $idFamilia = 0;
        $grupo = null;
        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required_unless:solicitante,false','string', Rule::unique(User::class)],
            'lastname' => ['required', 'string'],
            'dni' => ['required', Rule::unique(User::class)],
            'tipo_dni' => ['required'],
            'nacimiento' => ['required', 'date'],
            'password'=> ['required_unless:solicitante,false','string'],
            'solicitante' => ['present', 'boolean'],
            'vendedor_id' => ['required'],
            'paquete_id' => ['required'],
            //'sexo' => ['required', Rule::in(User::sexo)],
            'calle' => ['required'],
            'barrio' => ['required'],
            'nro_casa' => ['required'],
            'obra_social_id' => ['required'],
            'dni_solicitante' => ['required_unless:solicitante,true']
        ]);

        if($validador->fails())
        {
            return $this->onError(422,"Error de validación", $validador->errors());
        }

        if($request['solicitante']){
            $email = $request['email'];
            $password = bcrypt($request['password']);
        }else{
            $email = Str::random(12).$request['dni'].'@mail.com';
            $password = bcrypt(Str::random(12).$request['dni']);
            $solicitante = User::where('dni',$request['dni_solicitante'])->first();
            
            if(!isset($solicitante->afiliado))
            {
                return $this->onError(422,"el dni enviado no pertenece a ningun afiliado");
            }else
            {
                $idFamilia = $solicitante->afiliado->grupo_familiar_id;
            }
        }

        if($idFamilia <> 0){
            $grupo = $idFamilia;
        }else{
            $grupo = GrupoFamiliar::create([
                'apellido' => $request['lastname']
            ]);
            $grupo = $grupo->id;
        }

        $nacimiento = Carbon::parse($request['nacimiento'])->format('Y-m-d');
        $actual = Carbon::now();

        $usuario = User::create([
            'name'     => $request->name,
            'email'    => $email,
            'lastname' => $request->lastname,
            'dni'      => $request->dni,
            'tipo_dni' => $request->tipo_dni,
            'nacimiento' => $nacimiento,
            'edad'     => $actual->diffInYears($nacimiento),
            'password' => $password,
            //'sexo' => $request['sexo'],
            'role_id'  => \App\Models\Role::ES_AFILIADO,
        ]);

        $afiliado = $usuario->afiliado()->create([
            "codigo_afiliado" => Str::uuid(),
            "calle" => $request["calle"],
            "barrio" => $request["barrio"],
            "nro_casa" => $request["nro_casa"],
            "paquete_id" => $request["paquete_id"],
            "obra_social_id" => $request["obra_social_id"],
            'solicitante' => $request['solicitante'],
            'grupo_familiar_id' => $grupo
        ]);

        $afiliado->vendedores()->attach($request->vendedor_id);

        return $this->onSuccess(new AfiliadoResource($usuario),"Afiliado creado de manera correcta",201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
