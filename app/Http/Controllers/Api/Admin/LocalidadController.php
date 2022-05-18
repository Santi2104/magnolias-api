<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Library\LogHelpers;
use App\Http\Resources\Admin\LocalidadResource;
use App\Models\Localidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocalidadController extends Controller
{
    use ApiHelpers, LogHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $localidades = Localidad::with([
            'departamento' => function($query){
                $query->select('id','ndepartamento','provincia_id');
            },
            'departamento.provincia' => function($query){
                $query->select('id','nprovincia');
            }
        ])->get(['id','departamento_id','nlocalidad','codigo_postal']);
        return $this->onSuccess(LocalidadResource::collection($localidades));
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
            "nombre" => ['required','string','max:30'],
            "departamento_id" => ['required','exists:App\Models\Departamento,id'],
            "codigo_postal" => ['present']
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $localidad = Localidad::create([
            "nlocalidad" => $request["nombre"],
            'departamento_id' => $request['departamento_id'],
            'codigo_posta' => $request['codigo_postal']
        ]);

        $this->crearLog('Admin',"Creando Localidad", $request->user()->id,"Localidad",$request->user()->role->id,$request->path());
        return $this->onSuccess(new LocalidadResource($localidad),"Localidad creada de manera correcta", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $localidad = Localidad::with([
            'barrios' => function($query){
                $query->select('id','nbarrio AS nombre','localidad_id');
            },
            'calles' => function($query){
                $query->select('id','ncalle AS nombre','localidad_id');
            },
        ])
        ->where('id',$id)
        ->first(['id','nlocalidad AS nombre']);

        if(!isset($localidad)){
            return $this->onError(404,"Recurso no encontrado","El id provisto no pertenece a ningun recurso");
        }

        return $this->onSuccess($localidad);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validador = Validator::make($request->all(), [
            "nombre" => ['required','string','max:30'],
            "departamento_id" => ['required','exists:App\Models\Departamento,id'],
            "codigo_postal" => ['present'],
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $localidad = Localidad::find($id);
        if(!isset($localidad)){
            return $this->onError(404,"La localidad a la que intenta acceder no existe");
        }

        $localidad->nlocalidad = $request['nombre'];
        $localidad->codigo_postal = $request['codigo_postal'];

        if($localidad->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $localidad->save();
        $this->crearLog('Admin',"Editando Localidad", $request->user()->id,"Localidad",$request->user()->role->id,$request->path());
        return $this->onSuccess(new LocalidadResource($localidad),"Localidad modificada de forma correcta",200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $localidad = Localidad::find($id);
        
        if(!isset($localidad)){
            return $this->onError(404,"El localidad al que intenta acceder no existe");
        }

        $localidad->delete();

        return $this->onSuccess($localidad, "localidad eliminada de manera correcta");
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        $localidad = Localidad::withTrashed()->where('id', $id)->first();

        if(!isset($localidad)){
            return $this->onError(404,"La localidad al que intenta acceder no existe");
        }
        
        $localidad->restore();

        return $this->onSuccess($localidad,"Localidad restaurada");
    }
}
