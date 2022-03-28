<?php

namespace App\Http\Controllers\Api\Administrativo;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\Administrativo\VendedorResource;
use App\Models\User;
use App\Models\Vendedor;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if(!Auth::user()->tokenCan('vendedor:index'))
        {
            return $this->onError(403,"No está autorizado a realizar esta acción","Falta de permisos para acceder a este recurso");
        }
        
        $vendedores = User::whereRoleId(\App\Models\Role::ES_VENDEDOR)->get();

        return $this->onSuccess(VendedorResource::collection($vendedores));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->tokenCan('vendedor:store'))
        {
            return $this->onError(403,"No está autorizado a realizar esta acción","Falta de permisos para acceder a este recurso");
        }
        //TODO: Verificar de alguna forma que ese coordinador_id sea realmente un coordinador
        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required','string', Rule::unique(User::class)],
            'lastname' => ['required', 'string'],
            'dni' => ['required', Rule::unique(User::class)],
            'nacimiento' => ['required'],
            'password'=> ['required','string','confirmed'],
            'zona_id' => ['required'],
            'coordinador_id' => ['required']
        ]);

        if($validador->fails())
        {
            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $nacimiento = Carbon::parse($request['nacimiento']);
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
                'role_id'  => \App\Models\Role::ES_VENDEDOR,
            ]);

            $usuario->vendedor()->create([
                "codigo_vendedor" => Str::uuid(),
                "coordinador_id" => $request['coordinador_id']
            ]);
            
            $usuario->vendedor->zonas()->attach($request['zona_id']);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->onError(422,"Error al cargar los datos",$th->getMessage());
        }

        return $this->onSuccess(new VendedorResource($usuario),"Vendedor creado de manera correcta",201);

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
        if(!Auth::user()->tokenCan('vendedor:update'))
        {
            return $this->onError(403,"No está autorizado a realizar esta acción","Falta de permisos para acceder a este recurso");
        }

        $vendedor = Vendedor::whereCodigoVendedor($request['codigo_vendedor'])->first();

        if(!isset($vendedor))
        {
            return $this->onError(409,"No se puede encontrar el vendedor con el codigo enviado");
        }

        if(!$this->puedeEditar($vendedor->created_at))
        {
            return $this->onError(409,"Error al tratar de editar","Ha pasado el tiempo limite en que el registro se puede editar");
        }

        $usuario = $vendedor->user;

        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required','string', Rule::unique(User::class)->ignore($usuario->id)],
            'lastname' => ['required', 'string'],
            'dni' => ['required', Rule::unique(User::class)->ignore($usuario->id)],
            'nacimiento' => ['required'],
            'password'=> ['required','string'],
            'zona_id' => ['required'],
            'coordinador_id' => ['required']
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $nacimiento = Carbon::parse($request['nacimiento'])->format('Y-m-d');
        $actual = Carbon::now();

        try {
            DB::beginTransaction();
            $usuario->name = $request->name;
            $usuario->lastname = $request->lastname;
            $usuario->email = $request->email;
            $usuario->dni = $request->dni;
            $usuario->nacimiento = $nacimiento;
            $usuario->edad = $actual->diffInYears($nacimiento);
            $usuario->save();
    
            $usuario->vendedor()->update([
                'coordinador_id' => $request->coordinador_id
            ]);
            $usuario->vendedor->zonas()->sync($request->zona_id);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->onError(422,"Error al cargar los datos",$th->getMessage());
        }

        return $this->onSuccess(new VendedorResource($usuario),"Vendedor editado de manera correcta",201);

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
