<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creditos', function (Blueprint $table) {
            $table->increments('id_credito');
            $table->integer('id_estado')->nullable()->unsigned();
            $table->integer('id_estudiante')->nullable()->unsigned();
            $table->integer('id_instructor')->nullable()->unsigned();
            $table->integer('fecha_credito')->nullable();
            $table->integer('fecha_consumo_credito')->nullable();
            $table->text('url_checkout_payvalida')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_estado')->references('id_estado')->on('estados');
            $table->foreign('id_estudiante')->references('id_user')->on('usuarios');
            $table->foreign('id_instructor')->references('id_user')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creditos');
    }
}
