<?php

namespace App\Http\Controllers\Api\Administrativo;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\Administrativo\AfiliadoResource;
use App\Http\Resources\Administrativo\ShowAfiliadoResource;
use App\Models\Afiliado;
use App\Models\GrupoFamiliar;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        if(!Auth::user()->tokenCan('afiliado:index'))
        {
            return $this->onError(403,"No está autorizado a realizar esta acción","Falta de permisos para acceder a este recurso");
        }

        //$afiliados = User::whereRoleId(\App\Models\Role::ES_AFILIADO)->get();
        $afiliados = User::with([
            'afiliado' => function($query){
                $query->select('id','user_id','codigo_afiliado','paquete_id','solicitante');
            }
        ])
        ->where('role_id',\App\Models\Role::ES_AFILIADO)
        ->get();
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
        //TODO: Meter todo esto dentro de un servicio
        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string','max:25'],
            'email' => ['required_unless:solicitante,false', Rule::unique(User::class),'email'],
            'lastname' => ['required', 'string','max:25'],
            'dni' => ['required', Rule::unique(User::class),'max:9'],        
            'nacimiento' => ['required', 'date'],
            'tipo_dni' => ['required'],
            'solicitante' => ['present', 'boolean'],
            'vendedor_id' => ['required','exists:App\Models\Vendedor,id'],
            'paquete_id' => ['required','exists:App\Models\Paquete,id'],
            'sexo' => ['required', Rule::in(Afiliado::sexo)],
            'parentesco' => ['required_unless:solicitante,true',Rule::in(Afiliado::parentesco)],
            'calle' => ['required_unless:solicitante,false',],
            'barrio' => ['required_unless:solicitante,false'],
            'nro_casa' => ['required_unless:solicitante,false'],
            'cuil' => ['required_unless:solicitante,false'],
            'estado_civil' => ['required_unless:solicitante,false',Rule::in(Afiliado::estado_civil)],
            'profesion_ocupacion' => ['required_unless:solicitante,false'],
            'poliza_electronica' => ['required_unless:solicitante,false','boolean'],
            'obra_social_id' => ['required','exists:App\Models\ObraSocial,id'],
            'dni_solicitante' => ['required_unless:solicitante,true'],
            'nombre_tarjeta' => ['required_unless:solicitante,false','max:20'],
            'numero_tarjeta' => ['required_unless:solicitante,false'],
            'codigo_cvv' => ['required_unless:solicitante,false','max:3'],
            'tipo_tarjeta' => ['required_unless:solicitante,false','max:10'],
            'banco' => ['required_unless:solicitante,false','max:15'],
            'vencimiento_tarjeta' => ['required_unless:solicitante,false'],
            'titular_tarjeta' => ['required_unless:solicitante,false','max:40'],
            'codigo_postal' => ['required_unless:solicitante,false'],
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
            $request['cuil'] = null;
            $request['estado_civil'] = null;
            $request['profesion_ocupacion'] = null;
            $request['poliza_electronica'] = false;
            $request['nombre_tarjeta'] = null;
            $request['numero_tarjeta'] = null;
            $request['codigo_cvv'] = null;
            $request['tipo_tarjeta'] = null;
            $request['banco'] = null;
            $request['vencimiento_tarjeta'] = null;
            $request['titular_tarjeta'] = null;
            $solicitante = GrupoFamiliar::where('dni_solicitante', $request['dni_solicitante'])->first();
                                        // ->where('apellido', $request['lastname'])

            if(!isset($solicitante))
            {
                return $this->onError(422,"El DNI enviado no pertenece a ninguna solicitante");
            }else
            {
                $idFamilia = $solicitante->id;
            }
        }

        try {
            DB::beginTransaction();

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
                'grupo_familiar_id' => $grupo,
                'cuil' => $request['cuil'],
                'estado_civil' => $request['estado_civil'],
                'profesion_ocupacion' => $request['profesion_ocupacion'],
                'poliza_electronica' => $request['poliza_electronica'],
                'finaliza_en' => $this->calcularVencimiento(now()),
                'nombre_tarjeta' => $request['nombre_tarjeta'],
                'numero_tarjeta' => $request['numero_tarjeta'],
                'codigo_cvv' => $request['codigo_cvv'],
                'banco' => $request['banco'],
                'vencimiento_tarjeta' => $request['vencimiento_tarjeta'],
                'titular_tarjeta' => $request['titular_tarjeta'],
                'tipo_tarjeta' => $request['tipo_tarjeta'],
                'codigo_postal' => $request['codigo_postal'],
            ]);

            $afiliado->vendedores()->attach($request->vendedor_id);

            if($request['solicitante'])
            {
                $afiliado->pagos()->create([
                    'proximo_pago' => $this->calcularVencimiento(now()),
                    'paquete_id' => $request["paquete_id"],
                    'afiliado_id' => $afiliado->id,
                    'numero_comprobante' => $this->calcularComprobanteDePago()
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->onError(422,"Error al cargar los datos",$th->getMessage());
        }

        return $this->onSuccess(new AfiliadoResource($usuario),"Afiliado creado de manera correcta",201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if(!Auth::user()->tokenCan('afiliado:index'))
        {
            return $this->onError(403,"No está autorizado a realizar esta acción","Falta de permisos para acceder a este recurso");
        }

        $afiliado = Afiliado::where('codigo_afiliado',$request['codigo'])->first();

        if(!isset($afiliado))
        {
            return $this->onError(404,"No se puede encontrar al afiliado con el codigo enviado");
        }
        return $this->onSuccess(new ShowAfiliadoResource($afiliado));
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

    /**
     * Envia los datos del vendedor y el paquete de un afiliado
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function datosAfiliado(Request $request)
    {

        $validador = Validator::make($request->all(), [
            "dni" => ['required','exists:App\Models\User,dni'],
        ]);

        if($validador->fails())
        {
            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $usuario = User::with([
            'afiliado' => function($query){
                $query->select('id','user_id','codigo_afiliado','paquete_id','solicitante');
            },
            'afiliado.paquete' => function($query){
                $query->select('id','nombre');
            },
            'afiliado.vendedores' => function($query){
                $query->select('id','user_id','codigo_vendedor');
            },
            'afiliado.vendedores.user' => function($query){
                $query->select('id','name','lastname');
            }
        ])
        ->where('dni',$request->dni)
        ->first(['id','name','lastname','dni']);

        if(is_null($usuario->afiliado))
        {
            return $this->onError(422,'Error con el afiliado','El dni debe ser el de un afiliado solicitante');
        }

        if($usuario->afiliado->solicitante == false)
        {
            return $this->onError(422,'Error con el afiliado','El dni debe ser el de un afiliado solicitante');
        }


        return $this->onSuccess($usuario,'Afiliado encontrado');
    }
}
