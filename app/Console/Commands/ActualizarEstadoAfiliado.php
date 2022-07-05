<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ActualizarEstadoAfiliado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'afiliado:estado';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el estado del afiliado';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        //*Para probar cambiar la condicion despues del now()
        $afiliados = \App\Models\Afiliado::where('finaliza_en', '=',now()->format('Y-m-d'))
        ->where('activo',true)
        ->get();

        if(!isset($afiliados))
        {
            return Command::SUCCESS;
        }

        foreach($afiliados as $afiliado)
        {
            $afiliado->activo = false;
            $afiliado->save();
        }
    }
}
