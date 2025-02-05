<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluacionInterna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluacion_interna', function (Blueprint $table) {
            $table->increments('id_evaluacion_interna');
            $table->text('evaluacion_interna')->nullable();
            $table->integer('id_estudiante')->nullable()->unsigned();
            $table->integer('id_instructor')->nullable()->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_estudiante')->references('id_user')->on('usuarios');
            $table->foreign('id_instructor')->references('id_user')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluacion_interna');
    }
}
