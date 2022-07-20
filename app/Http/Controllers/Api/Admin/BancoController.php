<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Banco;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use App\Http\Library\LogHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BancoController extends Controller
{
    use ApiHelpers, LogHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bancos = Banco::all();
        return $this->onSuccess($bancos);
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
            "nombre" => ['required',Rule::unique(Banco::class,'nombre', 'max:80')],
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $banco = Banco::create([
            'nombre' => $request['nombre']
        ]);

        return $this->onSuccess($banco,"El Banco fue creado de manera correcta", 201);
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

        $banco = Banco::find($request['id']);

        if(!isset($banco))
        {
            return $this->onError(404,"Error en algunos campos","El Id enviado no existe");
        }

        $banco->fill($request->only([
            "nombre"
        ]));

        if($banco->isClean())
        {
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $banco->save();
        $this->crearLog('Admin',"Editando Banco", $request->user()->id,"Banco",$request->user()->role->id,$request->path());

        return $this->onSuccess($banco,"El Banco fue editado de manera correcta", 200);
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
