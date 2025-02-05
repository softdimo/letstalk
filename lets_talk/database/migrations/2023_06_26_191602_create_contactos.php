<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contactos', function (Blueprint $table) {
            $table->increments('id_contacto')->unsigned();
            $table->integer('id_user')->nullable()->unsigned();
            
            $table->integer('id_primer_contacto')->nullable()->unsigned();
            $table->string('primer_telefono')->nullable();
            $table->string('primer_celular')->nullable();
            $table->string('primer_correo')->nullable();
            $table->string('primer_skype')->nullable();
            $table->string('primer_zoom')->nullable();

            $table->integer('id_segundo_contacto')->nullable()->unsigned();
            $table->string('segundo_telefono')->nullable();
            $table->string('segundo_celular')->nullable();
            $table->string('segundo_correo')->nullable();
            $table->string('segundo_skype')->nullable();
            $table->string('segundo_zoom')->nullable();

            $table->integer('id_opcional_contacto')->nullable()->unsigned();
            $table->string('opcional_telefono')->nullable();
            $table->string('opcional_celular')->nullable();
            $table->string('opcional_correo')->nullable();
            $table->string('opcional_skype')->nullable();
            $table->string('opcional_zoom')->nullable();
            
            $table->foreign('id_user')->references('id_user')->on('usuarios');
            $table->foreign('id_primer_contacto')->references('id_tipo_contacto')->on('tipo_contacto');
            $table->foreign('id_segundo_contacto')->references('id_tipo_contacto')->on('tipo_contacto');
            $table->foreign('id_opcional_contacto')->references('id_tipo_contacto')->on('tipo_contacto');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contactos');
    }
}
