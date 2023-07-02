<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('usuario')->nullable();
            $table->bigInteger('numero_comprobante',false,true);
            $table->date('fecha_pago')->nullable();
            $table->date('proximo_pago');
            $table->string('metodo_pago')->nullable();
            $table->string('banco')->nullable();
            $table->string('tarjeta')->nullable();
            $table->string('nro_transaccion')->nullable();
            $table->integer('monto')->nullable();
            $table->unsignedBigInteger('paquete_id');
            $table->foreign('paquete_id')->references('id')->on('paquetes');
            $table->unsignedBigInteger('afiliado_id');
            $table->foreign('afiliado_id')->references('id')->on('afiliados');
            $table->boolean('pagado')->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}
