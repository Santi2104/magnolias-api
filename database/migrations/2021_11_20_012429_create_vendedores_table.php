<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained()
            ->onDelete('cascade')
            ->OnUpdate('cascade');
            $table->uuid('codigo_vendedor')->unique();
            $table->unsignedBigInteger('coordinador_id');
            $table->foreign('coordinador_id')->references('id')->on('coordinadores');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendedores');
    }
}
