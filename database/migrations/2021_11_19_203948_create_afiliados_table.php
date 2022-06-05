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
            $table->string('dni_solicitante')->nullable();
            $table->enum('sexo',['M','F']);
            $table->string('parentesco')->nullable();
            //?Crear clave foranea para relacionar el acta de solicitud
            //?Crear una clave foranea para relacionar al acta de seguro
            $table->string('cuit')->nullable();
            $table->string('cuil')->nullable();
            $table->string('lugar_nacimiento')->nullable();
            $table->string('domicilio')->nullable();
            $table->string('localidad')->nullable();
            $table->string('provincia')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('estado_civil',10)->nullable();
            $table->string('telefono_particular',20)->nullable();
            $table->string('profesion_ocupacion')->nullable();
            $table->boolean('poliza_electronica')->nullable();//Campo para saber si le mandamos la poliza por email al solicitante
            $table->string('trabajo')->nullable();
            $table->string('domicilio_laboral')->nullable();
            $table->string('localidad_laboral')->nullable();
            $table->string('provincia_laboral')->nullable();
            $table->string('codigo_postal_laboral')->nullable();
            $table->string('email_laboral')->nullable();
            $table->string('telefono_laboral')->nullable();
            $table->boolean('seguro_retiro')->nullable();//esta campo es booleano
            $table->string('nombre_tarjeta',20)->nullable();
            $table->string('numero_tarjeta')->nullable();
            $table->string('codigo_cvv',3)->nullable();
            $table->string('tipo_tarjeta',10)->nullable();
            $table->string('banco',15)->nullable();
            $table->string('vencimiento_tarjeta')->nullable();
            $table->string('titular_tarjeta',50)->nullable();
            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade')
            ->OnUpdate('cascade');
            $table->foreignId('paquete_id')
            ->constrained()
            ->onDelete('cascade')
            ->OnUpdate('cascade');
            $table->date('finaliza_en')->nullable();
            $table->date('ultimo_pago')->nullable();
            $table->boolean('activo')->default(false);
            $table->unsignedBigInteger('nro_solicitud');
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
