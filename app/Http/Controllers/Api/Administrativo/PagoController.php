<?php

namespace App\Http\Controllers\Api\Administrativo;

use App\Events\ActualizarAfiliado;
use App\Models\Pago;
use Illuminate\Http\Request;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use App\Http\Library\LogHelpers;
use App\Models\Afiliado;
use Illuminate\Support\Facades\Validator;

class PagoController extends Controller
{
    use ApiHelpers, LogHelpers;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'metodo_pago' => ['required'],
            'monto' => ['required', 'integer'],
            'banco' => ['present'],
            'tarjeta' => ['present'],
            'nro_transaccion' => ['present'],
            'proximo_pago' => ['date'],
            //'finaliza_en' => ['required','date'],
            'observaciones' => ['nullable'],
            'paquete_id' => ['required','exists:App\Models\Paquete,id'],
            'afiliado_id' => ['required','exists:App\Models\Afiliado,id']
        ]);

        if($validador->fails())
        {
            return $this->onError(422,"Error de validación", $validador->errors());
        }

        //$afiliado = Afiliado::whereId($request['afiliado_id'])->first(['id','solicitante','grupo_familiar_id','dni_solicitante']);
        $afiliado = Afiliado::with([
            'user' => function($query){
                $query->select('id','dni');
            }
        ])->whereId($request['afiliado_id'])->first(['id','solicitante','grupo_familiar_id','dni_solicitante','user_id','finaliza_en']);
        if(!$afiliado->solicitante)
        {
            return $this->onError(422,"Error en el afiliado", "El pago debe hacerse con un afiliado solicitante");
        }

        $pago = Pago::create([
            'fecha_pago' => now(),
            'proximo_pago' => $request->proximo_pago,
            'paquete_id' => $request->paquete_id,
            'afiliado_id' => $request->afiliado_id,
            'monto' => $request->monto,
            'banco' => $request->banco,
            'tarjeta' => $request->tarjeta,
            'metodo_pago' => $request->metodo_pago,
            'usuario' => $request->user()->name ." ". $request->user()->lastname,
            'observaciones' => $request['observaciones'],
            'numero_comprobante' => $this->calcularComprobanteDePago(),
            'nro_transaccion' => $request->nro_transaccion,
            'pagado' => true
        ]);

        event(new ActualizarAfiliado($pago,$afiliado));
        $this->crearLog('administrativo',"Creando Pago", $request->user()->id,"Pago",$request->user()->role->id,$request->path());
        return $this->onSuccess($pago,"Pago registrado de manera correcta",201);

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
    public function update(Request $request)
    {
        $validador = Validator::make($request->all(), [
            'metodo_pago' => ['required'],
            'monto' => ['required', 'integer'],
            'proximo_pago' => ['date'],
            'banco' => ['present'],
            'observaciones' => ['nullable'],
            'paquete_id' => ['required','exists:App\Models\Paquete,id'],
            'afiliado_id' => ['required','exists:App\Models\Afiliado,id'],
            'pago_id' => ['required','exists:App\Models\Pago,id'],
            'nro_transaccion' => ['present'],
        ]);

        if($validador->fails())
        {
            return $this->onError(422,"Error de validación", $validador->errors());
        }

        $pago = Pago::whereId($request['pago_id'])->first();

        if(!$this->puedeEditar($pago->created_at))
        {
            return $this->onError(409,"Error al tratar de editar","Ha pasado el tiempo limite en que el registro se puede editar");
        }
        $pago->metodo_pago = $request['metodo_pago'];
        $pago->monto = $request['monto'];
        $pago->proximo_pago = $request['proximo_pago'];
        $pago->observaciones = $request['observaciones'];
        $pago->paquete_id = $request['paquete_id'];
        $pago->afiliado_id = $request['afiliado_id'];
        $pago->nro_transaccion = $request['nro_transaccion'];
        $pago->save();

        $this->crearLog('administrativo',"Editando Pago", $request->user()->id,"Pago",$request->user()->role->id,$request->path());
        return $this->onSuccess($pago,"Pago registrado de manera correcta",201);
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
