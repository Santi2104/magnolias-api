<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGrupoFamiliarToAfiliados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('afiliados', function (Blueprint $table) {
            $table->unsignedBigInteger('grupo_familiar_id');
            $table->foreign('grupo_familiar_id')->references('id')->on('grupo_familiar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('afliados', function (Blueprint $table) {
            $table->dropColumn('obra_social_id');
        });
    }
}
