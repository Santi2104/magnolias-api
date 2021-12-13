<?php

namespace App\Http\Controllers\Api\Coordinador;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\Coordinador\UserVendedorResource;
use App\Http\Resources\Coordinador\VendedorAfiliadoResource;
use App\Http\Resources\Coordinador\VendedorResource;
use App\Models\User;
use App\Models\Vendedor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VendedoresController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coordinador_id = Auth::user()->coordinador->id;

        if(!isset($coordinador_id)){
            $this->onError(422,"Este usuario aun no se registró como coordinador. Contacte con un administrador para realizar dicha operacion");
        }

        $vendedores = Vendedor::where("coordinador_id", $coordinador_id)->get();

        if($vendedores->isEmpty()){

            return $this->onMessage(200,"Este coordinador aun no tiene vendedores asignados");
        }

        return VendedorResource::collection($vendedores);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //*TODO: La contraseña deberia crearse de manera aleatoria en este caso */
        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required','string', Rule::unique(User::class)],
            'lastname' => ['required', 'string'],
            'dni' => ['required'],
            'nacimiento' => ['required'],
            'password'=> ['required','string','confirmed'],
            'zona_id' => ['required'] 
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
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
            'role_id'  => \App\Models\Role::ES_VENDEDOR,
        ]);

        $usuario->vendedor()->create([
            "codigo_vendedor" => Str::uuid(),
            "zona_id" => $request["zona_id"],
            "coordinador_id" => Auth::user()->coordinador->id
        ]);

        return $this->onSuccess(new UserVendedorResource($usuario));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $coordinador_id = Auth::user()->coordinador->id;

        if(!isset($coordinador_id)){
            $this->onError(422,"Este usuario aun no se registró como coordinador. Contacte con un administrador para realizar dicha operacion");
        }

        $vendedor = Vendedor::where("coordinador_id", $coordinador_id)
                            ->where("codigo_vendedor", $uuid)
                            ->first();

        if(!isset($vendedor)){

            return $this->onError(404,"No existe un vendedor con el codigo enviado o no esta asociado al coordinador");
        }                    
                  
        return $this->onSuccess(new VendedorAfiliadoResource($vendedor));
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {   

        $vendedor = Vendedor::where('codigo_vendedor', $uuid)->first();

        if(!isset($vendedor)){

            return $this->onError(409,"No se puede encontrar al vendedor con el uuid enviado");
        }
        
        if ($request->user()->cannot('update', $vendedor)) {
            return $this->onError(403,"No tiene permisos para editar este vendedor");
        }

        $usuario = $vendedor->user;

        $validador = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required','string', Rule::unique(User::class)->ignore($usuario->id)],
            'lastname' => ['required', 'string'],
            'dni' => ['required', Rule::unique(User::class)->ignore($usuario->id)],
            'nacimiento' => ['required'],
            'password'=> ['required','string','confirmed'],
            'zona_id' => ['required'],
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $vendedor->zona_id = $request["zona_id"];
        $vendedor->coordinador_id = $request['coordinador_id'] ? $request['coordinador_id'] : Auth::user()->coordinador->id;
        

        $nacimiento = Carbon::parse($request['nacimiento'])->format('Y-m-d');
        $actual = Carbon::now();

        $usuario->name     = $request->name;
        $usuario->email    = $request->email;
        $usuario->lastname = $request->lastname;
        $usuario->dni      = $request->dni;
        $usuario->nacimiento = $nacimiento;
        $usuario->edad     = $actual->diffInYears($nacimiento);
        //? Un coordinar puede cambiar a un vendedor de coordinador?

        if($usuario->isClean() and $vendedor->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }
        $vendedor->save();
        $usuario->save();

        return $this->onSuccess(new VendedorAfiliadoResource($vendedor),"vendedor actualizado de manera correcta",200);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uncouple(Request $request)
    {
        //*TODO: Agregar soft delete a todos los modelos
    }
}
