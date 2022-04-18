<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Calle;
use Illuminate\Http\Request;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CalleController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $calles = Calle::with([
            'localidad' => function($query){
                $query->select('id','nlocalidad','codigo_postal');
            }
        ])->get(['id','ncalle','localidad_id']);

        return $this->onSuccess($calles);
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
            "nombre" => ['required','string','max:50'],
            "localidad_id" => ['required','exists:App\Models\Localidad,id'],
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $calle = Calle::create([
            'ncalle' => $request['nombre'],
            'localidad_id' => $request['localidad_id']
        ]);

        return $this->onSuccess($calle,"Calle creada de manera correcta", 201);
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
            "nombre" => ['required','string','max:50'],
            "localidad_id" => ['required','exists:App\Models\Localidad,id'],
            'id' => ['required','exists:App\Models\Calle,id'],
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $calle = Calle::whereId($request['id'])->first();
        $calle->ncalle = $request['nombre'];
        $calle->localidad_id = $request['localidad_id'];
        $calle->save();

        return $this->onSuccess($calle,"La calle se modifico de manera correcta", 201);

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
