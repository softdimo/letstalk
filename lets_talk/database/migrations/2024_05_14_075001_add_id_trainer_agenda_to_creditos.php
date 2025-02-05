<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdTrainerAgendaToCreditos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('creditos', function (Blueprint $table) {
            $table->unsignedInteger('id_trainer_agenda')->nullable()->after('id_instructor');

            $table->foreign('id_trainer_agenda')->references('id')->on('evento_agenda_entrenador');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('creditos', function (Blueprint $table) {
            $table->dropColumn('id_trainer_agenda');
        });
    }
}
