<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\Admin\ZonaResource;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZonaController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zonas = Zona::all();
        return $this->onSuccess(ZonaResource::collection($zonas));
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
            "localidad_id" => ['required']
        ]);

        if($validador->fails()){

            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 200);
        }

        $zona = Zona::create([
            "nombre" => $request["nombre"],
            "localidad_id" => $request["localidad_id"]
        ]);

        return $this->onSuccess(new ZonaResource($zona),"La zona fue creada de manera correcta",201);
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
            "localidad_id" => ['required']
        ]);

        if($validador->fails()){

            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 200);
        }

        $zona = Zona::find($id);
        if(!isset($zona)){
            return $this->onError(404,"La zona al que intenta acceder no existe");
        }

        $zona->fill($request->only([
            "nombre",
            "localidad_id"
        ]));

        if($zona->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $zona->save();

        return $this->onSuccess(new ZonaResource($zona),"Zona actualizado de manera correcta");
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
