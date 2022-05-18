<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Barrio;
use Illuminate\Http\Request;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use App\Http\Library\LogHelpers;
use Illuminate\Support\Facades\Validator;

class BarrioController extends Controller
{
    use ApiHelpers, LogHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $barrios = Barrio::with([
            'localidad' => function($query){
                $query->select('id','nlocalidad','codigo_postal');
            }
        ])->get(['id','nbarrio','localidad_id']);

        return $this->onSuccess($barrios);
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
            "localidad_id" => ['required','exists:App\Models\Localidad,id'],
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $barrio = Barrio::create([
            'nbarrio' => $request['nombre'],
            'localidad_id' => $request['localidad_id']
        ]);
        $this->crearLog('Admin',"Creando Barrio", $request->user()->id,"Barrio",$request->user()->role->id,$request->path());
        return $this->onSuccess($barrio,"Barrio creada de manera correcta", 201);
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
            "nombre" => ['required','string','max:30'],
            "localidad_id" => ['required','exists:App\Models\Localidad,id'],
            'id' => ['required','exists:App\Models\Barrio,id'],
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $barrio = Barrio::whereId($request['id'])->first();
        $barrio->nbarrio = $request['nombre'];
        $barrio->localidad_id = $request['localidad_id'];
        $barrio->save();

        $this->crearLog('Admin',"Editando VendedoBarrio", $request->user()->id,"VendedoBarrio",$request->user()->role->id,$request->path());
        return $this->onSuccess($barrio,"El barrio se modifico de manera correcta",200);
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
