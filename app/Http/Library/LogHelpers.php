<?php

namespace App\Http\Library;

use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Log;

trait LogHelpers
{
    protected function crearLog($accion,$usuario,$recurso,$rol,$ruta)
    {
        Log::channel('administrativo')->info($accion,[
            'user_id' => $usuario,
            'Recurso' => $recurso,
            'rol_id'  => $rol,
            'ruta' => $ruta
        ]);

        UserLog::create([
            'user' => Auth::user()->name. " " . Auth::user()->lastname,
            'accion' => $accion,
            'recurso' => $recurso,
            'ruta' => $ruta
        ]);
    }
}