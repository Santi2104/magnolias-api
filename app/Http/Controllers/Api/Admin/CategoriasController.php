<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\Admin\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriasController extends Controller
{

    use ApiHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::all();
        return $this->onSuccess(CategoriaResource::collection($categorias));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO: Verificar que la categoria que se quiera ingresar no se encuentre en la BD
        $validador = Validator::make($request->all(), [
            "nombre" => ['required']
        ]);

        if($validador->fails()){

            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 200);
        }

       $categoria = Categoria::create([
            'nombre' => $request['nombre']
        ]);

        return $this->onSuccess(new CategoriaResource($categoria),"Categoria creada de manera correcta",201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
    {
        $validador = Validator::make($request->all(), [
            "nombre" => ['required']
        ]);

        if($validador->fails()){

            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 200);
        }

        $categoria->fill($request->only([
            "nombre"
        ]));

        if($categoria->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $categoria->save();

        return $this->onSuccess(new CategoriaResource($categoria),"Categoria actualizada de manera correcta");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
}
