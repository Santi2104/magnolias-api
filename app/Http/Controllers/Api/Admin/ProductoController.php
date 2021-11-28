<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::all();
        return $this->onSuccess($productos,"",200);
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
            "nombre" => ['required'],
            "categoria_id" => ['required']
        ]);

        if($validador->fails()){

            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 200);
        }
        //TODO: Crear un resourse para los productos(tambien las categorias)
        $producto = Producto::create([
            "nombre" => $request['nombre'],
            "categoria_id" => $request['categoria_id']
        ]);

        return $this->onSuccess($producto,"Producto creado de manera correcta",201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = Producto::find($id);

        if(!isset($producto)){
            return $this->onError(404,"El producto al que intenta acceder no existe");
        }

        return $this->onSuccess($producto,"Producto encontrado",200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validador = Validator::make($request->all(), [
            "nombre" => ['required'],
            "categoria_id" => ['required']
        ]);

        if($validador->fails()){

            return response()->json([
                'status' => 200,
                'message' => $validador->errors(),
            ], 200);
        }

        $producto = Producto::find($id);
        if(!isset($producto)){
            return $this->onError(404,"El producto al que intenta acceder no existe");
        }

        $producto->fill($request->only([
            "nombre",
            "categoria_id"
        ]));

        if($producto->isClean()){
            return $this->onError(422,"Debe especificar al menos un valor diferente para poder actualizar");
        }

        $producto->save();

        return $this->onSuccess($producto,"Producto actualizado de manera correcta");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
