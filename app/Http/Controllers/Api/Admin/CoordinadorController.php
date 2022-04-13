<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\Admin\CoordinadorResource;
use App\Http\Resources\Admin\UserCoordinadorResource;
use App\Models\Coordinador;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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
        //*TODO:La contraseña deberia crearse de manera aleatoria en este caso */
        //*TODO:Cuando se aplique la confirmación de cuenta implementar lo de la contraseña*/
        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required','string', Rule::unique(User::class)],
            'lastname' => ['required', 'string'],
            'dni' => ['required'],
            'nacimiento' => ['required', 'date'],
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $nacimiento = Carbon::parse($request['nacimiento'])->format('Y-m-d');
        $actual = Carbon::now();

        $usuario = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'lastname' => $request->lastname,
            'dni'      => $request->dni,
            'nacimiento' => $nacimiento,
            'edad'     => $actual->diffInYears($nacimiento),
            'password' => bcrypt(Str::random(12).$request['dni']),
            'role_id'  => \App\Models\Role::ES_COORDINADOR,
        ]);

        $usuario->coordinador()->create([
            "codigo_coordinador" => Str::uuid()
        ]);

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
            'uuid' => ['required'],
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
        /**
            *?Talves aca Deberia Ir algo para modificar la tabla coordinador
            *? Si modifico el mail, deberia poder vericarlo de nuevo si es correcto
        */

        if($usuario->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $usuario->save();

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

        /**
     * Obtiene los vendedores de un coordinador especifico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function obtenerVendedor(Request $request)
    {

    }


}
