<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Library\LogHelpers;
use App\Models\ObraSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ObraSocialController extends Controller
{
    use ApiHelpers, LogHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $obraSociales = ObraSocial::all();
        return $this->onSuccess($obraSociales);
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
            "nombre" => ['required','string','max:15'],
        ]);

        if($validador->fails()){

            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 200);
        }

        $obraSocial = ObraSocial::create([
            "nombre" => $request["nombre"]
        ]);

        $this->crearLog('Admin',"Creando Obra social", $request->user()->id,"Obra social",$request->user()->role->id,$request->path());

        return $this->onSuccess($obraSocial, "Obra Social creada de manera correcta",201);
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
            "nombre" => ['required','string','max:15'],
        ]);

        if($validador->fails()){

            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 200);
        }

        $obraSocial = ObraSocial::find($id);
        if(!isset($obraSocial)){
            return $this->onError(404,"La obra social a la que intenta acceder no existe");
        }

        $obraSocial->fill($request->only([
            "nombre",
        ]));

        if($obraSocial->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $obraSocial->save();
        $this->crearLog('Admin',"Editando Obra social", $request->user()->id,"Obra social",$request->user()->role->id,$request->path());
        return $this->onSuccess($obraSocial, "Obra Social modificada de correcta",200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $obraSocial = ObraSocial::find($id);
        
        if(!isset($obraSocial)){
            return $this->onError(404,"La Obra Social a la que intenta acceder no existe");
        }

        $obraSocial->delete();
        $this->crearLog('Admin',"Eliminando Obra Social", $request->user()->id,"Obra Social",$request->user()->role->id,$request->path());
        return $this->onSuccess($obraSocial, "Obra Social eliminada de manera correcta");
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(Request $request, $id)
    {
        $obraSocial = ObraSocial::withTrashed()->where('id', $id)->first();

        if(!isset($obraSocial)){
            return $this->onError(404,"La Obra Social a la que intenta acceder no existe");
        }
        
        $obraSocial->restore();
        $this->crearLog('Admin',"Restaurando Obra Social", $request->user()->id,"Obra Social",$request->user()->role->id,$request->path());
        return $this->onSuccess($obraSocial,"Obra Social restaurada");
    }
}
