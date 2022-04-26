<?php

namespace App\Http\Controllers\Api\Administrativo;

use App\Models\Pago;
use Illuminate\Http\Request;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use App\Models\Afiliado;
use Illuminate\Support\Facades\Validator;

class PagoController extends Controller
{
    use ApiHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagos = Pago::with([
            'paquete' => function($query){
                $query->select('id','nombre','precio');
            },
            'afiliado' => function($query){
                $query->select('id','user_id','codigo_afiliado');
            },
            'afiliado.user' => function($query){
                $query->select('id','name','lastname','dni');
            }
        ])->get();

        return $this->onSuccess($pagos);
    }

    /**
     * Actualizar el pago de un afiliado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function establecerPago(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'id' => ['required','exists:App\Models\Pago,id'],
            'pago' => ['required'],
            'monto' => ['required', 'integer'],
        ]);

        if($validador->fails())
        {
            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $pago = Pago::where('id', $request['id'])->first();
        $pago->fecha_pago = now();
        $pago->pago = $request['pago'];
        $pago->monto = $request['monto'];
        $pago->pagado = true;
        $pago->usuario = $request->user()->name ." ". $request->user()->lastname;
        $pago->proximo_pago = $this->calcularVencimiento($pago->proximo_pago);
        $pago->save();

        Pago::create([
            'proximo_pago' => $this->calcularVencimiento($pago->proximo_pago),
            'paquete_id' => $pago->paquete_id,
            'afiliado_id' => $pago->afiliado_id,
            'numero_comprobante' => $this->calcularComprobanteDePago()
        ]);

        $afiliado = Afiliado::whereId($pago->afiliado_id)->first(['id','finaliza_en','ultimo_pago']);
        $afiliado->finaliza_en = $this->calcularVencimiento($afiliado->finaliza_en);
        $afiliado->ultimo_pago = now();
        $afiliado->save();

        return $this->onSuccess($pago,"Pago registrado de manera correcta",201);
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
