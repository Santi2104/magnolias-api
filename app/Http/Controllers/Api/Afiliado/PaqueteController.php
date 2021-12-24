<?php

namespace App\Http\Controllers\Api\Afiliado;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Models\Afiliado;
use Illuminate\Http\Request;

class PaqueteController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $afiliado = Afiliado::with([
            'paquete' => function($query){
                $query->select('id', 'nombre');
            },
            'user' => function($query){
                $query->select('id','name', 'lastname','email', 'dni');
            }
        ])->get();

        return $this->onSuccess($afiliado);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
