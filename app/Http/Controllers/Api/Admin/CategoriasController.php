<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Resources\Admin\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

        $validador = Validator::make($request->all(), [
            "nombre" => ['required','unique:categorias,nombre']
        ]);

        if($validador->fails()){

            return $this->onError(400,"Error de validación", $validador->errors());
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
            "nombre" => ['required', Rule::unique(Categoria::class)->ignore($categoria->id)]
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
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
        $categoria->delete();

        return $this->onSuccess($categoria, "Categoria eliminada de manera correcta");
    }

    public function restore($id)
    {
        $categoria = Categoria::withTrashed()->where('id', $id)->first();

        if(!isset($categoria)){
            return $this->onError(404,"El producto al que intenta acceder no existe");
        }

        $categoria->restore();

        return $this->onSuccess($categoria,"Categoria restaurada");
    }
}
