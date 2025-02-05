<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->increments('id_reserva');
            $table->integer('id_estudiante')->nullable()->unsigned();
            $table->integer('id_instructor')->nullable()->unsigned();
            $table->integer('id_horario')->nullable()->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_estudiante')->references('id_user')->on('usuarios');
            $table->foreign('id_instructor')->references('id_user')->on('usuarios');
            $table->foreign('id_horario')->references('id_horario')->on('disponibilidad_entrenadores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
