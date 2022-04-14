<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DepartamentoController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departamentos = Departamento::with([
            'provincia' => function($query){
                $query->select('id','nprovincia');
            }
        ])->get(['id','provincia_id','ndepartamento']);

        return $this->onSuccess($departamentos);
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
            "provincia_id" => ['required','exists:App\Models\Provincia,id'],
        ]);

        if($validador->fails()){
            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 422);
        }

        $departamento = Departamento::create([
            'ndepartamento' => $request['nombre'],
            'provincia_id' => $request['provincia_id']
        ]);

        return $this->onSuccess($departamento,"Departamento creado de manera correcta",201);
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
            "provincia_id" => ['required','exists:App\Models\Provincia,id'],
            'id' => ['required','exists:App\Models\Departamento,id'],
        ]);

        if($validador->fails()){
            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 422);
        }

        $departamento = Departamento::whereId($request['id'])->first();
        $departamento->ndepartamento = $request['nombre'];
        $departamento->provincia_id = $request['provincia_id'];
        $departamento->save();

        return $this->onSuccess($departamento,"El departamento se modifico de manera correcta",200);
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
