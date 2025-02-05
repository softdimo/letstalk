<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArchivoEvaluacionToEvaluacionInterna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluacion_interna', function (Blueprint $table) {
            $table->string('archivo_evaluacion')->nullable()->after('id_instructor');
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
            $table->dropColumn('archivo_evaluacion');
        });
    }
}
