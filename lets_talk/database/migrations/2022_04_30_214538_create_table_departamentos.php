<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDepartamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->increments('id_departamento');
            $table->integer('pais_id')->unsigned();
            $table->string('codigo_dane')->nullable();
            $table->string('descripcion');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pais_id')->references('id_pais')->on('paises');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departamentos');
    }
}
