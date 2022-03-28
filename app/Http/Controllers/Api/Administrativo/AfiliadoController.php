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
        if(!Auth::user()->tokenCan('vendedor:index'))
        {
            return $this->onError(403,"No está autorizado a realizar esta acción","Falta de permisos para acceder a este recurso");
        }

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
        //TODO: Meter todo esto dentro de un servicio y una transaccion
        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required_unless:solicitante,false', Rule::unique(User::class),'email'],
            'lastname' => ['required', 'string'],
            'dni' => ['required', Rule::unique(User::class)],
            'tipo_dni' => ['required'],
            'nacimiento' => ['required', 'date'],
            'solicitante' => ['present', 'boolean'],
            'vendedor_id' => ['required'],
            'paquete_id' => ['required'],
            'sexo' => ['required', Rule::in(Afiliado::sexo)],
            'parentesco' => ['required_unless:solicitante,true',Rule::in(Afiliado::parentesco)],
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
            $password = bcrypt(Str::random(12).$request['dni']);
            $request['parentesco'] = null;
            
        }else{
            $email = Str::random(12).$request['dni'].'@mail.com';
            $password = bcrypt(Str::random(12).$request['dni']);
            $solicitante = GrupoFamiliar::where('dni_solicitante', $request['dni_solicitante'])
                                        ->where('apellido', $request['lastname'])->first();
                           
            if(!isset($solicitante))
            {
                return $this->onError(422,"El DNI enviado no pertenece a ninguna solicitante o el apellido no coincide");
            }else
            {
                $idFamilia = $solicitante->id;
            }
        }

        if($idFamilia <> 0){
            $grupo = $idFamilia;
        }else{
            $grupo = GrupoFamiliar::create([
                'apellido' => $request['lastname'],
                'dni_solicitante' => $request['dni']
            ]);
            $grupo = $grupo->id;
        }

        $usuario = User::create([
            'name'     => $request->name,
            'email'    => $email,
            'lastname' => $request->lastname,
            'dni'      => $request->dni,
            'tipo_dni' => $request->tipo_dni,
            'nacimiento' => Carbon::parse($request['nacimiento'])->format('Y-m-d'),
            'edad'     => $this->calcularEdad($request['nacimiento']),
            'password' => $password,
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
            'sexo' => $request['sexo'],
            'parentesco' => $request['parentesco'],
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
