<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Tarjeta;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Library\ApiHelpers;
use App\Http\Library\LogHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TarjetaController extends Controller
{
    use ApiHelpers, LogHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tarjetas = Tarjeta::all();
        return $this->onSuccess($tarjetas);
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
            "nombre" => ['required',Rule::unique(Tarjeta::class,'nombre', 'max:40')],
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $tarjeta = Tarjeta::create([
            'nombre' => $request['nombre']
        ]);

        return $this->onSuccess($tarjeta,"La tarjeta fue creada de manera correcta", 201);

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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validador = Validator::make($request->all(), [
            "nombre" => ['required'],
            'id' => ['required'],
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $tarjeta = Tarjeta::find($request['id']);

        if(!isset($tarjeta))
        {
            return $this->onError(404,"Error en algunos campos","El Id enviado no existe");
        }

        $tarjeta->fill($request->only([
            "nombre"
        ]));

        if($tarjeta->isClean())
        {
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $tarjeta->save();
        $this->crearLog('Admin',"Editando Tarjeta", $request->user()->id,"Tarjeta",$request->user()->role->id,$request->path());
        return $this->onSuccess($tarjeta,"La tarjeta fue editada de manera correcta", 200);

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
