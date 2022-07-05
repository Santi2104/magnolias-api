<?php

namespace App\Http\Controllers\Api\Administrativo;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Library\LogHelpers;
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
    use ApiHelpers, LogHelpers;
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

        //$vendedores = User::whereRoleId(\App\Models\Role::ES_VENDEDOR)->get();
        $vendedores = User::with([
            'vendedor' => function($query){
                $query->select('id','user_id','codigo_vendedor','coordinador_id');
            },
            'vendedor.user' => function($query){
                $query->select('id','name','lastname','email','dni');
            },
            'vendedor.coordinador' => function($query){
                $query->select('id','user_id','codigo_coordinador');
            },
            'vendedor.coordinador.user' => function($query){
                $query->select('id','name','lastname','email','dni');
            }
        ])
        ->where('role_id',\App\Models\Role::ES_VENDEDOR)
        ->get(['id','name','lastname','email','dni']);

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

        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string','max:25'],
            'email' => ['required','email', Rule::unique(User::class)],
            'username' => ['required','string','max:30',Rule::unique(User::class)],
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

        $nacimiento = Carbon::parse($request['nacimiento']);
        $actual = Carbon::now();

        try {
            DB::beginTransaction();

            $usuario = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'lastname' => $request->lastname,
                'dni'      => $request->dni,
                'username' => $request->username,
                'nacimiento' => $nacimiento,
                'edad'     => $actual->diffInYears($nacimiento),
                'password' => bcrypt(Str::random(12).$request['dni']),
                'role_id'  => \App\Models\Role::ES_VENDEDOR,
            ]);

            $usuario->vendedor()->create([
                "codigo_vendedor" => Str::uuid(),
                "coordinador_id" => $request['coordinador_id']
            ]);

            $usuario->vendedor->localidades()->attach($request['localidad_id']);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->onError(422,"Error al cargar los datos",$th->getMessage());
        }
        $this->crearLog('administrativo',"Creando Vendedor", $request->user()->id,"Vendedor",$request->user()->role->id,$request->path());
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
            'name' => ['required', 'string','max:25'],
            'email' => ['required','email', Rule::unique(User::class)->ignore($usuario->id)],
            'username' => ['required','string','max:30',Rule::unique(User::class)->ignore($usuario->id)],
            'lastname' => ['required', 'string','max:25'],
            'dni' => ['required','string',Rule::unique(User::class)->ignore($usuario->id),'max:9'],
            'nacimiento' => ['required','date'],
            'localidad_id' => ['required', 'exists:App\Models\Localidad,id'],
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
            $usuario->username = $request->username;
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

        $this->crearLog('administrativo',"Editando Vendedor", $request->user()->id,"Vendedor",$request->user()->role->id,$request->path());
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
