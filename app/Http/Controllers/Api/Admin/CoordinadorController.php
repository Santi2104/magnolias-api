<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Library\LogHelpers;
use App\Http\Resources\Admin\CoordinadorResource;
use App\Http\Resources\Admin\UserCoordinadorResource;
use App\Models\Coordinador;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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
        $coordinadores = Coordinador::all();
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
        //*TODO:Cuando se aplique la confirmación de cuenta implementar lo de la contraseña*/
        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string','max:25'],
            'email' => ['required','email', Rule::unique(User::class)],
            'lastname' => ['required', 'string','max:25'],
            'dni' => ['required', Rule::unique(User::class),'max:9'],
            'nacimiento' => ['required', 'date'],
            'username' => ['required','string','max:30',Rule::unique(User::class)],
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        try {
            DB::beginTransaction();
            $usuario = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'lastname' => $request->lastname,
                'dni'      => $request->dni,
                'username' => $request->username,
                'nacimiento' => Carbon::parse($request['nacimiento'])->format('Y-m-d'),
                'edad'     => $this->calcularEdad($request['nacimiento']),
                'password' => bcrypt(Str::random(12).$request['dni']),
                'role_id'  => \App\Models\Role::ES_COORDINADOR,
            ]);
    
            $usuario->coordinador()->create([
                "codigo_coordinador" => Str::uuid()
            ]);
            $this->crearLog('Admin',"Creando Coordinador", $request->user()->id,"Coordinador",$request->user()->role->id,$request->path());
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->onError(422,"Error al cargar los datos",$th->getMessage());
        }

        return $this->onSuccess(new UserCoordinadorResource($usuario),"coordinador creado de manera correcta",201);

    }

    /**
     * Display the specified resource.
     *
     * @param @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'uuid' => ['required','uuid','exists:App\Models\Coordinador,codigo_coordinador'],
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $coordinador = Coordinador::whereCodigoCoordinador($request['uuid'])
                    ->with([
                        'user' => function($query){
                            $query->select('id','name','lastname','dni','email');
                        },
                        'vendedores' => function($query){
                            $query->select('id','user_id','codigo_vendedor','coordinador_id');
                        },
                        'vendedores.user' => function($query){
                            $query->select('id','name','lastname','dni','email');
                        }
                    ])
                    ->first();

        return $this->onSuccess($coordinador);
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

        $coordinador = Coordinador::where("codigo_coordinador", $request['uuid'])->first();

        if(!isset($coordinador)){

            return $this->onError(409,"No se puede encontrar el coordinador con el uuid enviado");
        }

        $usuario = $coordinador->user;
        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string','max:25'],
            'email' => ['required','email', Rule::unique(User::class)->ignore($usuario->id)],
            'lastname' => ['required', 'string','max:25'],
            'dni' => ['required','string',Rule::unique(User::class)->ignore($usuario->id),'max:9'],
            'nacimiento' => ['required','date'],
            'username' => ['required','string','max:30',Rule::unique(User::class)->ignore($usuario->id)],
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->lastname = $request->lastname;
        $usuario->dni = $request->dni;
        $usuario->nacimiento = Carbon::parse($request['nacimiento'])->format('Y-m-d');
        $usuario->edad = $this->calcularEdad($request['nacimiento']);
        $usuario->username = $request->username;
        /**
            *?Talves aca Deberia Ir algo para modificar la tabla coordinador
            *? Si modifico el mail, deberia poder vericarlo de nuevo si es correcto
        */

        if($usuario->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $usuario->save();
        $this->crearLog('Admin',"Editando Coordinador", $request->user()->id,"Coordinador",$request->user()->role->id,$request->path());
        return $this->onSuccess(new UserCoordinadorResource($usuario),"Coordinador actualizado de manera correcta",200);
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
