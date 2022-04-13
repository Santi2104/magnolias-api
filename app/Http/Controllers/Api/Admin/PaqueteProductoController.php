<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\Admin\PaqueteProductoResource;
use App\Models\Paquete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaqueteProductoController extends Controller
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
        return $this->onSuccess(PaqueteProductoResource::collection($paquetes));
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
            "paquete_id" => ['required', 'exists:App\Models\Paquete,id'],
            "productos_id" => ['required','array','exists:App\Models\Producto,id']
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $paquete = Paquete::find($request["paquete_id"]);

        if(!isset($paquete)){

            return $this->onError(404,"No se puede encontrar un Paquete con el ID indicado");
        }

        $paquete->productos()->syncWithoutDetaching($request["productos_id"]);

        return $this->onSuccess(new PaqueteProductoResource($paquete),"Productos agregados al paquete de manera correcta");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paquete = Paquete::find($id);

        if(!isset($paquete)){

            return $this->onError(404,"No se puede encontrar un Paquete con el ID indicado");
        }
        
        return $this->onSuccess(new PaqueteProductoResource($paquete));
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
            "productos_id" => ['required','array','exists:App\Models\Producto,id']
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $paquete = Paquete::find($id);

        if(!isset($paquete)){

            return $this->onError(404,"No se puede encontrar un Paquete con el ID indicado");
        }
        
        $paquete->productos()->sync($request["productos_id"]);

        return $this->onSuccess(new PaqueteProductoResource($paquete),
                                "Los productos del paquete ".$paquete->nombre." fueron actualizados",
                                200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $validador = Validator::make($request->all(), [
            "productos_id" => ['required','array','exists:App\Models\Producto,id']
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $paquete = Paquete::find($id);

        if(!isset($paquete)){

            return $this->onError(404,"No se puede encontrar un Paquete con el ID indicado");
        }

        $paquete->productos()->detach($request["productos_id"]);

        return $this->onSuccess(new PaqueteProductoResource($paquete),
                                "Los productos seleccionados del paquete $paquete->nombre fueron removidos",
                                200);
    }
}
