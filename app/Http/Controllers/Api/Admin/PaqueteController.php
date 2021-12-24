<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Models\Paquete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaqueteController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paquetes = Paquete::all();
        return $this->onSuccess($paquetes);
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
            "nombre" => ['required',Rule::unique(Paquete::class)],
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $paquete = Paquete::create([
            "nombre" => $request["nombre"]
        ]);

        return $this->onSuccess($paquete,"Paquete creado de manera correcta",201);
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

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $paquete = Paquete::find($id);
        if(!isset($paquete)){
            return $this->onError(404,"El paquete al que intenta acceder no existe");
        }

        $paquete->fill($request->only([
            "nombre",
        ]));

        if($paquete->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $paquete->save();

        return $this->onSuccess($paquete,"Paquete actualizado de manera correcta");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paquete = Paquete::find($id);
        
        if(!isset($paquete)){
            return $this->onError(404,"El paquete al que intenta acceder no existe");
        }

        $paquete->delete();

        return $this->onSuccess($paquete, "Paquete eliminado de manera correcta");
    }

    public function restore($id)
    {
        $paquete = Paquete::withTrashed()->where('id', $id)->first();

        if(!isset($paquete)){
            return $this->onError(404,"El paquete al que intenta acceder no existe");
        }
        
        $paquete->restore();

        return $this->onSuccess($paquete,"Paquete restaurado");
    }
}
