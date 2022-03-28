<?php

namespace App\Http\Controllers\Api\Administrativo;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
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
    use ApiHelpers;
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
            'name' => ['required', 'string'],
            'email' => ['required','string', Rule::unique(User::class)],
            'lastname' => ['required', 'string'],
            'dni' => ['required'],
            'nacimiento' => ['required', 'date'],
            'password'=> ['required','string','confirmed'], 
        ]);

        if($validador->fails())
        {
            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $nacimiento = Carbon::parse($request['nacimiento'])->format('Y-m-d');
        $actual = Carbon::now();

        try {
            DB::beginTransaction();
            $usuario = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'lastname' => $request->lastname,
                'dni'      => $request->dni,
                'nacimiento' => $nacimiento,
                'edad'     => $actual->diffInYears($nacimiento),
                'password' => bcrypt($request->password),
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
        //TODO:Agregar la condicion de que solo se pueda modificar pasadas las 24 horas y agregarlo al ApiHelper
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
            'name' => ['required', 'string'],
            'email' => ['required','string', Rule::unique(User::class)->ignore($usuario->id)],
            'lastname' => ['required', 'string'],
            'dni' => ['required', Rule::unique(User::class)->ignore($usuario->id)],
            'nacimiento' => ['required', 'date'],
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $nacimiento = Carbon::parse($request['nacimiento'])->format('Y-m-d');
        $actual = Carbon::now();

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->lastname = $request->lastname;
        $usuario->dni = $request->dni;
        $usuario->nacimiento = $nacimiento;
        $usuario->edad = $actual->diffInYears($nacimiento);

        if($usuario->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $usuario->save();
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
