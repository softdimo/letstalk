<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdUsuarioToEventoAgendaEntrenador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evento_agenda_entrenador', function (Blueprint $table) {
            $table->unsignedInteger('id_usuario')->nullable()->after('color');

            $table->foreign('id_usuario')->references('id_user')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evento_agenda_entrenador', function (Blueprint $table) {
            $table->dropColumn('id_usuario');
        });
    }
}
