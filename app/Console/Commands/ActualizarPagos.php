<?php

namespace App\Console\Commands;

use App\Http\Library\ApiHelpers;
use Illuminate\Console\Command;

class ActualizarPagos extends Command
{
    use ApiHelpers;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actualizar:pagos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        //return Command::SUCCESS;
        //\Log::debug('An informational message.');
        $pagos = \App\Models\Pago::where('proximo_pago', '=',now()->addMonths(3)->format('Y-m-d'))
                        ->where('pagado', false)
                        ->where('recurrente',true)
                        ->get();

        foreach($pagos as $pago)
        {
            \App\Models\Pago::create([
            'proximo_pago' => $this->calcularVencimiento($pago->proximo_pago),
            'paquete_id' => $pago->paquete_id,
            'afiliado_id' => $pago->afiliado_id,
            'numero_comprobante' => $this->calcularComprobanteDePago(),
            ]);
            $pago->afiliado()->update([
                'activo' => false
            ]);
            $pago->recurrente = false;
            $pago->save();
        }

    }
}
