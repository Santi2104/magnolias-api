<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Library\LogHelpers;
use App\Models\Paquete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaqueteController extends Controller
{
    use ApiHelpers, LogHelpers;
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
            "nombre" => ['required','string','max:15',Rule::unique(Paquete::class)],
            "precio" => ['required','integer','min:0','max:10000']
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $paquete = Paquete::create([
            "nombre" => $request["nombre"],
            "precio" => $request['precio']
        ]);
        $this->crearLog('Admin',"Creando Paquete", $request->user()->id,"Paquete",$request->user()->role->id,$request->path());
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
            "nombre" => ['required','string','max:15',Rule::unique(Paquete::class)->ignore($id)],
            "precio" => ['required','integer','min:0','max:10000']
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
            "precio"
        ]));

        if($paquete->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $paquete->save();
        $this->crearLog('Admin',"Editando Paquete", $request->user()->id,"Paquete",$request->user()->role->id,$request->path());
        return $this->onSuccess($paquete,"Paquete actualizado de manera correcta");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $paquete = Paquete::find($id);
        
        if(!isset($paquete)){
            return $this->onError(404,"El paquete al que intenta acceder no existe");
        }

        $paquete->delete();
        $this->crearLog('Admin',"Eliminando Paquete", $request->user()->id,"Paquete",$request->user()->role->id,$request->path());
        return $this->onSuccess($paquete, "Paquete eliminado de manera correcta");
    }

    public function restore(Request $request,$id)
    {
        $paquete = Paquete::withTrashed()->where('id', $id)->first();

        if(!isset($paquete)){
            return $this->onError(404,"El paquete al que intenta acceder no existe");
        }
        
        $paquete->restore();
        $this->crearLog('Admin',"Restaurando Paquete", $request->user()->id,"Paquete",$request->user()->role->id,$request->path());
        return $this->onSuccess($paquete,"Paquete restaurado");
    }
}
