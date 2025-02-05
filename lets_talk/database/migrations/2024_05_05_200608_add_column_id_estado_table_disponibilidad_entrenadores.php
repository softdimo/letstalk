<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIdEstadoTableDisponibilidadEntrenadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disponibilidad_entrenadores', function (Blueprint $table) {
            $table->integer('id_estado')->unsigned()->nullable()->after('horario');
            $table->foreign('id_estado')->references('id_estado')->on('estados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('disponibilidad_entrenadores', function (Blueprint $table) {
            $table->dropColumn('id_estado');
        });
    }
}
