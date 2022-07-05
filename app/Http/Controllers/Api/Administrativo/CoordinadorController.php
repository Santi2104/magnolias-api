<?php

namespace App\Http\Controllers\Api\Administrativo;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Library\LogHelpers;
use App\Http\Resources\Administrativo\CoordinadorResource;
use App\Models\Coordinador;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CoordinadorController extends Controller
{
    use ApiHelpers, LogHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $user = Auth::user();
        if(!$user->tokenCan('coordinador:index')){
            return $this->onError(403,"No esta autorizado a realizar esta accion","Falta de permisos para acceder a este recurso");
        }

        $coordinadores = User::whereRoleId(\App\Models\Role::ES_COORDINADOR)->get();
        return $this->onSuccess(CoordinadorResource::collection($coordinadores));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO:Tratar de enviar esta logica al ApiHelper y a una clase de servicios
        if(!$request->user()->tokenCan('coordinador:store')){
            return $this->onError(403,"No está autorizado a realizar esta acción","Falta de permisos para acceder a este recurso");
        }

        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string','max:25'],
            'email' => ['required','email', Rule::unique(User::class)],
            'username' => ['required','string','max:30',Rule::unique(User::class)],
            'lastname' => ['required', 'string','max:25'],
            'dni' => ['required', Rule::unique(User::class),'max:9'],
            'nacimiento' => ['required', 'date'], 
        ]);

        if($validador->fails())
        {
            return $this->onError(422,"Error de validación", $validador->errors());
        }

        try {
            DB::beginTransaction();
            $usuario = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'dni'      => $request->dni,
                'nacimiento' => Carbon::parse($request['nacimiento'])->format('Y-m-d'),
                'edad'     => $this->calcularEdad($request['nacimiento']),
                'password' => bcrypt(Str::random(12).$request['dni']),
                'role_id'  => \App\Models\Role::ES_COORDINADOR,
            ]);
    
            $usuario->coordinador()->create([
                "codigo_coordinador" => Str::uuid()
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->onError(422,"Error al cargar los datos",$th->getMessage());
        }
        $this->crearLog('administrativo',"Creando Coordinador", $request->user()->id,"Coordinador",$request->user()->role->id,$request->path());
        return $this->onSuccess(new CoordinadorResource($usuario),"coordinador creado de manera correcta",201);
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
    public function update(Request $request)
    {
        if(!$request->user()->tokenCan('coordinador:update')){
            return $this->onError(403,"No está autorizado a realizar esta acción","Falta de permisos para acceder a este recurso");
        }

        $coordinador = Coordinador::where("codigo_coordinador", $request['uuid'])->first();

        if(!isset($coordinador)){

            return $this->onError(409,"No se puede encontrar el coordinador con el codigo enviado");
        }

        if(!$this->puedeEditar($coordinador->created_at))
        {
            return $this->onError(409,"Error al tratar de editar","Ha pasado el tiempo limite en que el registro se puede editar");
        }

        $usuario = $coordinador->user;

        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string','max:25'],
            'email' => ['required','email', Rule::unique(User::class)->ignore($usuario->id)],
            'lastname' => ['required', 'string','max:25'],
            'username' => ['required','string','max:30',Rule::unique(User::class)->ignore($usuario->id)],
            'dni' => ['required','string',Rule::unique(User::class)->ignore($usuario->id),'max:9'],
            'nacimiento' => ['required','date'],
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->lastname = $request->lastname;
        $usuario->username = $request->username;
        $usuario->dni = $request->dni;
        $usuario->nacimiento = Carbon::parse($request['nacimiento'])->format('Y-m-d');
        $usuario->edad = $this->calcularEdad($request['nacimiento']);

        if($usuario->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $usuario->save();
        $this->crearLog('administrativo',"Editando Coordinador", $request->user()->id,"Coordinador",$request->user()->role->id,$request->path());
        return $this->onSuccess(new CoordinadorResource($usuario),"Coordinador actualizado de manera correcta",200);

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
