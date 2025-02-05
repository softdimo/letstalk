<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDiaEventoAgendaEntrenadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evento_agenda_entrenador', function (Blueprint $table) {
            $table->integer('num_dia')->nullable()->after('id_horario');
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
            $table->dropColumn('num_dia');
        });
    }
}
