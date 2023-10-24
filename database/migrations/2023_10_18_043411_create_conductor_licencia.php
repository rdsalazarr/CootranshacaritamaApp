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
        Schema::create('conductorlicencia', function (Blueprint $table) {
            $table->increments('conlicid')->unsigned()->comment('Identificador de la tabla conductor licencia');
            $table->integer('asocid')->unsigned()->comment('Identificador del asociado');

            $table->string('ticaliid', 2)->comment('Identificador del tipo de categoría de licencia');
            $table->timestamps();
            
            $table->foreign('ticaliid')->references('ticaliid')->on('tipocategorialicencia')->onUpdate('cascade')->index('fk_ticalicond');
        });
    }
    
    /*  `id` int NOT NULL AUTO_INCREMENT,
  `conductor_id` int NOT NULL,
  `licencia` varchar(45) NOT NULL,
  `tipo_licencia` varchar(45) NOT NULL,
  `expedicion` varchar(45) NOT NULL,
  `vencimiento` date NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `imagen_2` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `licencia_at` varchar(45) DEFAULT NULL,
  `licencia_in` varchar(45) DEFAULT NULL,*/


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conductor_licencia');
    }
};
