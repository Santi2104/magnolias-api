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
            $table->string('nro_casa');
            $table->string('nro_depto')->nullable();
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
