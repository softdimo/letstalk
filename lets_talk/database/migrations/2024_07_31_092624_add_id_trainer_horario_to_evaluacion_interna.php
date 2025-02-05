<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdTrainerHorarioToEvaluacionInterna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluacion_interna', function (Blueprint $table) {
            $table->unsignedInteger('id_trainer_horario')->nullable()->after('archivo_evaluacion');

            $table->foreign('id_trainer_horario')->references('id')->on('evento_agenda_entrenador');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluacion_interna', function (Blueprint $table) {
            $table->dropColumn('id_trainer_horario');
        });
    }
}
