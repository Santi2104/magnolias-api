<?php

namespace App\Listeners;

use App\Events\ActualizarAfiliado;
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
        $event->pago->afiliado()->update([
            'ultimo_pago' => now(),
            'finaliza_en' => $event->finaliza_en,
            'activo' => true
        ]);

        //$grupo_familiar = \App\Models\Afiliado::where('id',$event->pago->afiliado_id)->first(['id','grupo_familiar_id']);
        $afiliados = \App\Models\Afiliado::where('dni_solicitante',$event->afiliado->user->dni)->get();
        
        foreach($afiliados as $afiliado)
        {
            $afiliado->ultimo_pago = now();
            $afiliado->finaliza_en = $event->finaliza_en;
            $afiliado->activo = true;
            $afiliado->save();
        }


    }
}
