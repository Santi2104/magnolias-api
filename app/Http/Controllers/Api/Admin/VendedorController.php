<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\Admin\VendedoresResource;
use App\Models\User;
use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VendedorController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendedores = User::whereRoleId(\App\Models\Role::ES_VENDEDOR)->get();
        return $this->onSuccess(VendedoresResource::collection($vendedores));
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
            'email' => ['required','email', Rule::unique(User::class)],
            'lastname' => ['required', 'string','max:25'],
            'dni' => ['required', Rule::unique(User::class),'max:9'],
            'nacimiento' => ['required', 'date'],
            'localidad_id' => ['required', 'exists:App\Models\Localidad,id'],
            'coordinador_id' => ['required','exists:App\Models\Coordinador,id']
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
                'dni'      => $request->dni,
                'nacimiento' => Carbon::parse($request['nacimiento'])->format('Y-m-d'),
                'edad'     => $this->calcularEdad($request->nacimiento),
                'password' => bcrypt(Str::random(12).$request['dni']),
                'role_id'  => \App\Models\Role::ES_VENDEDOR,
            ]);

            $usuario->vendedor()->create([
                'coordinador_id' => $request->coordinador_id,
                "codigo_vendedor" => Str::uuid(),
            ]);

            $usuario->vendedor->localidades()->attach($request->localidad_id);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->onError(422,"Error al cargar los datos",$th->getMessage());
        }

        return $this->onSuccess(new VendedoresResource($usuario),"Vendedor editado de manera correcta",201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'uuid' => ['required','exists:App\Models\Vendedor,codigo_vendedor'],
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $vendedor = Vendedor::whereCodigoVendedor($request->uuid)
                    ->with([
                        'user' => function($query){
                            $query->select('id','name','lastname','dni','email');
                        },
                        'coordinador' => function($query){
                            $query->select('id','user_id','codigo_coordinador');
                        },
                        'coordinador.user' => function($query){
                            $query->select('id','name','lastname','dni','email');
                        },
                        'afiliados' => function($query){
                            $query->select('id','user_id','codigo_afiliado');
                        },
                        'afiliados.user' => function($query){
                            $query->select('id','name','lastname','dni','email');
                        },
                    ])->first();
        
        return $vendedor;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $vendedor = Vendedor::whereCodigoVendedor($request['codigo_vendedor'])->first();

        if(!isset($vendedor))
        {
            return $this->onError(409,"No se puede encontrar el vendedor con el codigo enviado");
        }

        $usuario = $vendedor->user;

        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string','max:25'],
            'email' => ['required','email', Rule::unique(User::class)->ignore($usuario->id)],
            'lastname' => ['required', 'string','max:25'],
            'dni' => ['required','string',Rule::unique(User::class)->ignore($usuario->id),'max:9'],
            'nacimiento' => ['required','date'],
            'localidad_id' => ['required','exists:App\Models\Localidad,id'],
            'coordinador_id' => ['required','exists:App\Models\Coordinador,id']
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        try {
            DB::beginTransaction();
            $usuario->name = $request->name;
            $usuario->lastname = $request->lastname;
            $usuario->email = $request->email;
            $usuario->dni = $request->dni;
            $usuario->nacimiento = $request->nacimiento;
            $usuario->edad = $this->calcularEdad($request->nacimiento);
            $usuario->save();

            $usuario->vendedor()->update([
                'coordinador_id' => $request->coordinador_id
            ]);

            $usuario->vendedor->localidades()->sync($request->localidad_id);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->onError(422,"Error al cargar los datos",$th->getMessage());
        }

        return $this->onSuccess(new VendedoresResource($usuario),"Vendedor editado de manera correcta",201);
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
