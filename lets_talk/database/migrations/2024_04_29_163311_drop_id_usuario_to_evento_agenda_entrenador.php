<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropIdUsuarioToEventoAgendaEntrenador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evento_agenda_entrenador', function (Blueprint $table) {
            $table->dropColumn(['id_usuario']);
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
            //
        });
    }
}
