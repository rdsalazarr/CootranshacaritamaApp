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
        Schema::create('vehiculo', function (Blueprint $table) {
            $table->increments('vehiid')->unsigned()->comment('Identificador de la tabla vehículo');
            $table->smallInteger('tipvehid')->unsigned()->comment('Identificador del tipo de vehículo');
            $table->smallInteger('tireveid')->unsigned()->comment('Identificador del tipo de referencia del vehículo');
            $table->smallInteger('timaveid')->unsigned()->comment('Identificador del tipo marca vehículo');
            $table->smallInteger('ticoveid')->unsigned()->comment('Identificador del tipo color vehículo');
            $table->tinyInteger('timoveid')->unsigned()->comment('Identificador del tipo modalidad vehículo');
            $table->smallInteger('ticaveid')->unsigned()->comment('Identificador del tipo carroceria vehículo');
            $table->tinyInteger('ticovhid')->unsigned()->comment('Identificador del tipo combustible vehículo');
            $table->smallInteger('agenid')->unsigned()->comment('Identificador de la agencia a la que esta asignado el vehículo');
            $table->string('tiesveid', 2)->comment('Identificador del tipo estado vehículo');
            $table->date('vehifechaingreso')->comment('Fecha de ingreso del vehículo a la cooperativa');
            $table->string('vehinumerointerno', 4)->comment('Número interno del vehículo');
            $table->string('vehiplaca', 8)->unique('uk_vehiculoplaca')->comment('Placa del vehículo');
            $table->string('vehimodelo', 4)->comment('Modelo del vehículo');
            $table->string('vehicilindraje', 6)->nullable()->comment('Cilindraje del vehículo');
            $table->string('vehinumeromotor', 30)->nullable()->comment('Número del motor del vehículo');
            $table->string('vehinumerochasis', 30)->nullable()->comment('Número del chasis del vehículo');
            $table->string('vehinumeroserie', 30)->nullable()->comment('Número del serie del vehículo');
            $table->string('vehinumeroejes', 4)->nullable()->comment('Número del chasis del vehículo');
            $table->boolean('vehiesmotorregrabado')->default(false)->comment('Determina si el vehículo tiene motor regrabado');
            $table->boolean('vehieschasisregrabado')->default(false)->comment('Determina si el vehículo tiene chasis regrabado');
            $table->boolean('vehiesserieregrabado')->default(false)->comment('Determina si el vehículo tiene serie regrabado'); 
            $table->string('vehirutafoto', 100)->nullable()->comment('Ruta de la foto del vehículo'); 

            $table->timestamps();
            $table->foreign('tipvehid')->references('tipvehid')->on('tipovehiculo')->onUpdate('cascade')->index('fk_tipvehvehi');
            $table->foreign('tireveid')->references('tireveid')->on('tiporeferenciavehiculo')->onUpdate('cascade')->index('fk_tirevevehi');
            $table->foreign('timaveid')->references('timaveid')->on('tipomarcavehiculo')->onUpdate('cascade')->index('fk_timavevehi');
            $table->foreign('ticoveid')->references('ticoveid')->on('tipocolorvehiculo')->onUpdate('cascade')->index('fk_ticovevehi');
            $table->foreign('timoveid')->references('timoveid')->on('tipomodalidadvehiculo')->onUpdate('cascade')->index('fk_timovevehi');
            $table->foreign('ticaveid')->references('ticaveid')->on('tipocarroceriavehiculo')->onUpdate('cascade')->index('fk_ticavevehi');
            $table->foreign('ticovhid')->references('ticovhid')->on('tipocombustiblevehiculo')->onUpdate('cascade')->index('fk_ticovhvehi');
            $table->foreign('agenid')->references('agenid')->on('agencia')->onUpdate('cascade')->index('fk_agenvehi');
            $table->foreign('tiesveid')->references('tiesveid')->on('tipoestadovehiculo')->onUpdate('cascade')->index('fk_tiesvevehi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculo');
    }
};
