<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClaseEstadoToEventoAgendaEntrenador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evento_agenda_entrenador', function (Blueprint $table) {
            $table->unsignedInteger('clase_estado')->nullable()->after('num_dia');

            $table->foreign('clase_estado')->references('id_estado')->on('estados');
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
            $table->dropColumn('clase_estado');
        });
    }
}
