<?php

namespace App\Listeners;

use App\Events\ActualizarAfiliado;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ActualizarEstadoDelAfiliado
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ActualizarAfiliado  $event
     * @return void
     */
    public function handle(ActualizarAfiliado $event)
    {

        $finaliza = Carbon::parse($event->afiliado->finaliza_en)->addMonth()->format('Y-m-d');

        $event->pago->afiliado()->update([
            'ultimo_pago' => now(),
            'finaliza_en' => $finaliza,
            'activo' => true
        ]);

        $afiliados = \App\Models\Afiliado::where('dni_solicitante',$event->afiliado->user->dni)->get();

        foreach($afiliados as $afiliado)
        {
            $afiliado->ultimo_pago = now();
            $afiliado->finaliza_en = $finaliza;
            $afiliado->activo = true;
            $afiliado->save();
        }


    }
}
