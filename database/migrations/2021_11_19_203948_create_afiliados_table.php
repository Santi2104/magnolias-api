<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAfiliadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afiliados', function (Blueprint $table) {
            $table->id();
            $table->uuid('codigo_afiliado')->unique();
            $table->string('calle');
            $table->string('barrio');
            $table->string('nro_casa')->nullable();
            $table->string('nro_depto')->nullable();
            $table->boolean('solicitante')->default(false)->comment("Esta campo indica si este afiliado fue el que solicito el servicio");
            //?Crear clave foranea para relacionar el acta de solicitud
            //?Crear una clave foranea para relacionar al acta de seguro
            $table->string('CUIT')->nullable();
            $table->string('CUIL')->nullable();
            $table->string('lugar_nacimiento')->nullable();
            $table->string('domicilio')->nullable();
            $table->string('localidad')->nullable();
            $table->string('provincia')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('telefono_particular')->nullable();
            $table->string('profesion_ocupacion')->nullable();
            $table->string('poliza_electronica')->nullable();//Campo para saber si le mandamos la poliza por email al solicitante
            $table->string('trabajo')->nullable();
            $table->string('domicilio_laboral')->nullable();
            $table->string('localidad_laboral')->nullable();
            $table->string('provincia_laboral')->nullable();
            $table->string('codigo_postal_laboral')->nullable();
            $table->string('email_laboral')->nullable();
            $table->string('telefono_laboral')->nullable();
            $table->string('seguro_retiro')->nullable();//esta campo es booleano
            //?El conyuge es automaticamente un beneficiaro?
            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade')
            ->OnUpdate('cascade');
            $table->foreignId('paquete_id')
            ->constrained()
            ->onDelete('cascade')
            ->OnUpdate('cascade');
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
        Schema::dropIfExists('afiliados');
    }
}
