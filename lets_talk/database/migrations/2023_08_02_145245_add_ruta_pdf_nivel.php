<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRutaPdfNivel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('niveles', function (Blueprint $table) {
            $table->string('ruta_pdf_nivel')->nullable()->after('nivel_descripcion');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('niveles', function (Blueprint $table) {
            $table->dropColumn('ruta_pdf_nivel');
        });
    }
}
