<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnsNullableInUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->integer('id_tipo_documento')->unsigned()->nullable()->change();
            $table->integer('id_municipio_nacimiento')->unsigned()->nullable()->change();
            $table->integer('id_municipio_residencia')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->integer('id_tipo_documento')->unsigned()->nullable(false)->change();
            $table->integer('id_municipio_nacimiento')->unsigned()->nullable(false)->change();
            $table->integer('id_municipio_residencia')->unsigned()->nullable(false)->change();
        });
    }
}
