<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuscripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->id();
            $table->uuid('comprobante')->unique();
            $table->uuid('codigo_afiliado');
            $table->foreign('codigo_afiliado')->references('codigo_afiliado')->on('afiliados');
            $table->unsignedBigInteger('paquete_id');
            $table->foreign('paquete_id')->references('id')->on('paquetes');
            $table->boolean('renovar')->default(true);
            $table->dateTime('inicia_en');
            $table->dateTime('finaliza_en');
            $table->dateTime('termina_en');
            $table->boolean('estado')->default(false);
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
        Schema::dropIfExists('suscripciones');
    }
}
