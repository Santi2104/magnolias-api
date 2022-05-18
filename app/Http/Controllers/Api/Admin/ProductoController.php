<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use App\Http\Library\LogHelpers;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\ProductoResource;

class ProductoController extends Controller
{
    use ApiHelpers, LogHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::all();
        return $this->onSuccess(ProductoResource::collection($productos));
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
            "nombre" => ['required','string','max:15','unique:productos,nombre'],
        ]);

        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $producto = Producto::create([
            "nombre" => $request['nombre'],
        ]);
        $this->crearLog('Admin',"Creando Producto", $request->user()->id,"Producto",$request->user()->role->id,$request->path());
        return $this->onSuccess(new ProductoResource($producto),"Producto creado de manera correcta",201);
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

        return $this->onSuccess(new ProductoResource($producto),"Producto encontrado",200);
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
            "nombre" => ['required','string','max:15',Rule::unique(Producto::class)->ignore($id)],
        ]);
        //*TODO: Realizar la validacion de arriba a todos los modelos
        if($validador->fails()){

            return $this->onError(422,"Error de validación", $validador->errors());
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
        $this->crearLog('Admin',"Editando Producto", $request->user()->id,"Producto",$request->user()->role->id,$request->path());
        return $this->onSuccess(new ProductoResource($producto),"Producto actualizado de manera correcta");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $producto = Producto::find($id);

        if(!isset($producto)){
            return $this->onError(404,"El producto al que intenta acceder no existe");
        }

        $producto->delete();
        $this->crearLog('Admin',"Eliminando Producto", $request->user()->id,"Producto",$request->user()->role->id,$request->path());
        return $this->onSuccess($producto, "Producto eliminada de manera correcta");
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(Request $request,$id)
    {
        $producto = Producto::withTrashed()->where('id', $id)->first();

        if(!isset($producto)){
            return $this->onError(404,"El producto al que intenta acceder no existe");
        }

        $producto->restore();
        $this->crearLog('Admin',"Restaurando Producto", $request->user()->id,"Producto",$request->user()->role->id,$request->path());
        return $this->onSuccess($producto,"Producto restaurado");
    }
}
