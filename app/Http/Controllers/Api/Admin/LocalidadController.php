<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\Admin\LocalidadResource;
use App\Models\Localidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocalidadController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $localidades = Localidad::all();
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
            "nombre" => ['required'],
        ]);

        if($validador->fails()){

            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 200);
        }

        $localidad = Localidad::create([
            "nombre" => $request["nombre"]
        ]);

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
        //
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
            "nombre" => ['required'],
        ]);

        if($validador->fails()){

            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 200);
        }

        $localidad = Localidad::find($id);
        if(!isset($localidad)){
            return $this->onError(404,"La localidad a la que intenta acceder no existe");
        }

        $localidad->fill($request->only([
            "nombre"
        ]));

        if($localidad->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $localidad->save();

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
