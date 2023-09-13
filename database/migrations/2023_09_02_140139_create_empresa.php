<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {        
        Schema::create('empresa', function (Blueprint $table) {
            $table->tinyIncrements('emprid')->comment('Identificador de la tabla empresa'); 
            $table->integer('persidrepresentantelegal')->unsigned()->comment('Identificador de la tabla persona');
            $table->tinyInteger('emprdepaid')->unsigned()->comment('Identificador del departamento');
            $table->smallInteger('emprmuniid')->nullable()->unsigned()->comment('Identificador del municipio');
            $table->string('emprnit', 15)->comment('Nit de la empresa');
            $table->string('emprdigitoverificacion', 1)->comment('Dígito de verificación de la empresa');
            $table->string('emprnombre', 100)->comment('Nombre de la empresa');
            $table->string('emprsigla', 20)->nullable()->comment('Sigla de la empresa');
            $table->string('emprlema', 100)->nullable()->comment('Lema de la empresa');
            $table->string('emprdireccion', 80)->comment('Dirección de la empresa');
            $table->string('emprcorreo', 80)->nullable()->comment('Correo de la empresa');
            $table->string('emprtelefonofijo', 20)->nullable()->comment('Teléfono fijo de contacto con la empresa');
            $table->string('emprtelefonocelular', 20)->nullable()->comment('Teléfono celular de contacto con la empresa');
            $table->string('emprhorarioatencion', 200)->nullable()->comment('Horario de atención');
            $table->string('emprurl', 100)->nullable()->comment('Url de la página web institucional');
            $table->string('emprcodigopostal', 10)->nullable()->comment('Código postal');
            $table->string('emprlogo', 80)->nullable()->comment('Logo de la empresa en en formato png');
            $table->timestamps();
            $table->foreign('emprdepaid')->references('depaid')->on('departamento')->index('fk_depaempr');
            $table->foreign('emprmuniid')->references('muniid')->on('municipio')->index('fk_muniempr');
            $table->foreign('persidrepresentantelegal')->references('persid')->on('persona')->onUpdate('cascade')->index('fk_persempr');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
