<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Pais;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PaisController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paises = Pais::all(['id','npais','activo']);
        return $this->onSuccess($paises);
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
            "nombre" => ['required','string','max:25',Rule::unique(Pais::class,'npais')],
        ]);

        if($validador->fails()){
            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 422);
        }

        $pais = Pais::create([
            'npais' => $request["nombre"]
        ]);

        return $this->onSuccess($pais,"El paise fue cargado de manera correcta",201);
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
            'id' => ['required','exists:App\Models\Pais,id'],
            "nombre" => ['required','string','max:25',Rule::unique(Pais::class,'npais')->ignore($request['nombre'],'npais')],
        ]);

        if($validador->fails()){
            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 422);
        }

        $pais = Pais::whereId($request['id'])->first();
        $pais->npais = $request['nombre'];
        $pais->save();

        return $this->onSuccess($pais,"El pais se actualizó de manera correcta");
        
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
