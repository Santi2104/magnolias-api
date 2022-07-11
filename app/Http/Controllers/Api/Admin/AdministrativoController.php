<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Library\LogHelpers;
use App\Http\Resources\Admin\AdministrativoResource;
use App\Http\Resources\Admin\UserAdministrativoResource;
use App\Models\Administrativo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Str;
use Validator;

class AdministrativoController extends Controller
{

    use ApiHelpers, LogHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $administrativos = Administrativo::all();
        return $this->onSuccess(AdministrativoResource::collection($administrativos));
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
            'name' => ['required', 'string','max:25'],
            'username' => ['required','string','max:30',Rule::unique(User::class)],
            'email' => ['nullable','email'],
            'lastname' => ['required', 'string','max:25'],
            'dni' => ['required', Rule::unique(User::class),'max:9'],
            'nacimiento' => ['required', 'date'],
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        try {
            DB::beginTransaction();
            $usuario = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'username' => $request->username,
                'lastname' => $request->lastname,
                'dni'      => $request->dni,
                'nacimiento' => Carbon::parse($request['nacimiento'])->format('Y-m-d'),
                'edad'     => $this->calcularEdad($request['nacimiento']),
                'password' => bcrypt($request->dni),
                'role_id'  => \App\Models\Role::ES_ADMINISTRATIVO,
            ]);
    
            $usuario->administrativo()->create([
                "codigo_administrativo" => Str::uuid()
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->onError(422,"Error al cargar los datos",$th->getMessage());
        }

        $this->crearLog('Admin',"Creando Administrativo", $request->user()->id,"Administrativo",$request->user()->role->id,$request->path());
        return $this->onSuccess(
            new UserAdministrativoResource($usuario),
            "Administrativo creado de manera correcta",
            201
        );
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $administrativo = Administrativo::where("codigo_administrativo", $request['uuid'])->first();

        if(!isset($administrativo)){

            return $this->onError(409,"No se puede encontrar el administrativo con el uuid enviado");
        }

        $usuario = $administrativo->user;

        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string','max:25'],
            'username' => ['required','string','max:30',Rule::unique(User::class)->ignore($usuario->id)],
            'password' => ['present'],
            'email' => ['nullable','email'],
            'lastname' => ['required', 'string','max:25'],
            'dni' => ['required','string',Rule::unique(User::class)->ignore($usuario->id),'max:9'],
            'nacimiento' => ['required','date'],
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $password = "";

        if(is_null($request['password']))
        {
            $password = $request->dni;
        }else
        {
            $password = $request['password'];
        }

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->username = $request->username;
        $usuario->password = bcrypt($password);
        $usuario->lastname = $request->lastname;
        $usuario->dni = $request->dni;
        $usuario->nacimiento = Carbon::parse($request['nacimiento'])->format('Y-m-d');
        $usuario->edad = $this->calcularEdad($request['nacimiento']);
        
        if($usuario->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        try {
            DB::beginTransaction();
            $usuario->save();
            $this->crearLog('Admin',"Editando Administrativo", $request->user()->id,"Administrativo",$request->user()->role->id,$request->path());
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->onError(422,"Error al cargar los datos",$th->getMessage());
        }

        return $this->onSuccess(
            new UserAdministrativoResource($usuario),
            "Administrativo modificado de manera correcta",
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'id' => ['required','exists:App\Models\Administrativo,id'],
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $administrativo =  Administrativo::find($request['id']);

        $administrativo->user()->delete();
        $administrativo->delete();
        $administrativo->save();

        return $this->onSuccess($administrativo,"Administrativo eliminado de manera correcta");
    }

    public function resetEmail(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'id' => ['required','exists:App\Models\Administrativo,id'],
            'dni' => ['required']
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $administrativo = Administrativo::find($request['id']);

        $usuario = $administrativo->user;

        // if($usuario->reset_email){
        //     return $this->onMessage(422,"Esta cuenta ya se encuentra reiniciada");
        // }

        $administrativo->user()->update([
            'username' => $usuario->cuil,
            'password' => bcrypt($request['dni']),
        ]);

        return $this->onMessage(201,"La cuenta del usuario fue reiniciado de manera correcta");
 
    }
}
