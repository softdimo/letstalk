<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id_user');
            $table->string('usuario')->unique();
            $table->string('password');
            $table->string('nombres')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('numero_documento')->nullable();
            $table->integer('id_tipo_documento')->unsigned();
            $table->integer('id_municipio_nacimiento')->unsigned();
            $table->integer('fecha_nacimiento')->nullable();
            $table->char('genero', 2)->nullable();
            $table->boolean('estado')->default(1);
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->string('correo')->nullable()->unique();
            $table->string('direccion_residencia')->nullable();
            $table->integer('id_municipio_residencia')->unsigned();
            $table->integer('fecha_ingreso_sistema')->nullable();
            $table->integer('id_rol')->unsigned();
            $table->integer('clave_fallas')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_municipio_nacimiento')->references('id_municipio')->on('municipios');
            $table->foreign('id_municipio_residencia')->references('id_municipio')->on('municipios');
            $table->foreign('id_tipo_documento')->references('id')->on('tipo_documento');
            $table->foreign('id_rol')->references('id_rol')->on('roles');
            $table->foreign('estado')->references('id_estado')->on('estados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
