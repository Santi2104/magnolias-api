<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Provincia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use App\Http\Library\LogHelpers;
use Illuminate\Support\Facades\Validator;

class ProvinciaController extends Controller
{
    use ApiHelpers, LogHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provincias = Provincia::with([
            'pais' => function($query){
                $query->select('id','npais');
            }
        ])->get(['id','nprovincia','pais_id']);

        return $this->onSuccess($provincias);
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
            "nombre" => ['required','string','max:25',Rule::unique(Provincia::class,'nprovincia')],
            "pais_id" => ['required','exists:App\Models\Pais,id'],
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $provincia = Provincia::create([
            'nprovincia' => $request['nombre'],
            'pais_id' => $request['pais_id']
        ]);

        $this->crearLog('Admin',"Creando Provincia", $request->user()->id,"Provincia",$request->user()->role->id,$request->path());
        return $this->onSuccess($provincia,"Provincia creada de manera correcta",201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $provincia = Provincia::with([
            'departamentos' => function($query){
                $query->select('id','ndepartamento','provincia_id');
            }
        ])
        ->where('id',$id)
        ->first(['id','nprovincia AS nombre']);

        if(!isset($provincia)){
            return $this->onError(404,"Recurso no encontrado","El id provisto no pertenece a ningun recurso");
        }

        return $this->onSuccess($provincia,"recurso encontrado");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validador = Validator::make($request->all(), [
            "nombre" => ['required','string','max:25',Rule::unique(Provincia::class,'nprovincia')->ignore($request['nombre'],'nprovincia')],
            "pais_id" => ['required','exists:App\Models\Pais,id'],
            'id' => ['required','exists:App\Models\Provincia,id'],
        ]);

        if($validador->fails()){
            return $this->onError(422,"Error de validacion",$validador->errors());
        }

        $provincia = Provincia::whereId($request['id'])->first();
        $provincia->nprovincia = $request['nombre'];
        $provincia->pais_id = $request['pais_id'];
        $provincia->save();

        $this->crearLog('Admin',"Editando Provincia", $request->user()->id,"Provincia",$request->user()->role->id,$request->path());
        return $this->onSuccess($provincia,"La provincia se actualizó de manera correcta");

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
