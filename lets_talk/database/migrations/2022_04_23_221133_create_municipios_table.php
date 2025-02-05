<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->increments('id_municipio');
            $table->integer('id_departamento');
            $table->string('codigo_dane')->nullable();
            $table->string('descripcion');
            $table->string('abreviatura')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('estado')->references('id_estado')->on('estados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipios');
    }
}
