<?php

namespace App\Http\Controllers\Api\Vendedores;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\Vendedor\AllAfiliadoResource;
use App\Http\Resources\Vendedor\ShowAfiliadoResource;
use App\Models\Afiliado;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AfiliadosController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendedor = Auth::user()->vendedor;
        
        if(!isset($vendedor)){
            $this->onError(422,"Este usuario aun no se registró como vendedor. Contacte con un administrador para realizar dicha operacion");
        }

        $afiliados = $vendedor->afiliados;

        if($afiliados->isEmpty()){

            $this->onMessage(200,"No se encontraron afiliados para este vendedor");
        }

        return $this->onSuccess(AllAfiliadoResource::collection($afiliados));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required','string', Rule::unique(User::class)],
            'lastname' => ['required', 'string'],
            'dni' => ['required'],
            'nacimiento' => ['required'],
            'password'=> ['required','string','confirmed'],
        ]);

        if($validador->fails()){

            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 200);
        }

        $nacimiento = Carbon::parse($request['nacimiento']);
        $actual = Carbon::now();

        $usuario = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'lastname' => $request->lastname,
            'dni'      => $request->dni,
            'nacimiento' => $nacimiento,
            'edad'     => $actual->diffInYears($nacimiento),
            'password' => bcrypt($request->password),
            'role_id'  => \App\Models\Role::ES_AFILIADO,
        ]);

        $afiliado = $usuario->afiliado()->create([
            "codigo_afiliado" => Str::uuid(),
            "calle" => $request["calle"],
            "barrio" => $request["barrio"],
            "nro_casa" => $request["nro_casa"],
            "paquete_id" => $request["paquete_id"],
            "obra_social_id" => $request["obra_social_id"],
        ]);

        $afiliado->vendedores()->attach(Auth::user()->vendedor->id);

        return $this->onSuccess($usuario,"Afiliado creado de manera correcta",201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        if(!$this->checkUuid($uuid)){
            return $this->onError(422,"EL ID enviado no tiene el formato correcto");
        }

        $afiliado = Afiliado::where("codigo_afiliado", $uuid)->first();

        if(!isset($afiliado)){
            return $this->onError(404,"No se puede encontrar el afiliado con el ID indicado");
        }

        return $this->onSuccess(new ShowAfiliadoResource($afiliado),"Afiliado encontrado");
    }

    /**
     * Update the specified afiliado in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAfiliado(Request $request, $uuid)
    {

        if(!$this->checkUuid($uuid)){
            return $this->onError(422,"EL ID enviado no tiene el formato correcto");
        }

        $validador = Validator::make($request->all(), [
            "calle" => ["required"],
            "barrio" => ["required"],
            "nro_casa" => ["required"],
            "paquete_id" => ["required"],
            "obra_social_id" => ["required"],
        ]);

        if($validador->fails()){

            return $this->onError(422,"Existen algunos errores en los datos enviados", $validador->errors());
        }

        $afiliado = Afiliado::where("codigo_afiliado", $uuid)->first();

        if(!isset($afiliado)){
            return $this->onError(404,"No se puede encontrar el afiliado con el ID indicado");
        }

        $afiliado->calle = $request['calle'];
        $afiliado->barrio = $request['barrio'];
        $afiliado->nro_casa = $request['nro_casa'];
        $afiliado->paquete_id = $request['paquete_id'];
        $afiliado->obra_social_id = $request['obra_social_id'];

        if($afiliado->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $afiliado->save();

        return $this->onSuccess(new ShowAfiliadoResource($afiliado),"Afiliado actualizado");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //*TODO:Aplicar a todos los modelos el soft delete
    }
}
