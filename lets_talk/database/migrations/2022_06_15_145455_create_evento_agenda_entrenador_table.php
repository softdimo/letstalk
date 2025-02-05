<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventoAgendaEntrenadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evento_agenda_entrenador', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100);
            $table->string('description', 255);
            $table->integer('start_date');
            $table->string('start_time', 10);
            $table->integer('end_date');
            $table->string('end_time', 10);
            $table->boolean('state')->default(2);
            $table->string('color', 20)->nullable();
            $table->integer('id_usuario')->unsigned()->nullable();
            $table->integer('id_horario')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_usuario')->references('id_user')->on('usuarios');
            $table->foreign('id_horario')->references('id_horario')->on('disponibilidad_entrenadores');
            $table->foreign('state')->references('id_estado')->on('estados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evento_agenda_entrenador');
    }
}
