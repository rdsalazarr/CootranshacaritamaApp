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
        Schema::create('encomienda', function (Blueprint $table) {
            $table->increments('encoid')->unsigned()->comment('Identificador de la tabla encomienda');
            $table->smallInteger('agenid')->unsigned()->comment('Identificador de la agencia que esta registrando la encomienda');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que crea el registro de la encomienda');
            $table->integer('plarutid')->unsigned()->comment('Identificador de la planilla ruta');
            $table->integer('perseridremitente')->unsigned()->comment('Identificador de la persona que envia la encomienda');
            $table->integer('perseriddestino')->unsigned()->comment('Identificador de la persona que recibe la encomienda');
            $table->tinyInteger('encodepaidorigen')->unsigned()->comment('Identificador del departamento de origen de la encomienda');
            $table->smallInteger('encomuniidorigen')->unsigned()->comment('Identificador del municipio de origen de la encomienda');
            $table->tinyInteger('encodepaiddestino')->unsigned()->comment('Identificador del departamento de destino de la encomienda');
            $table->smallInteger('encomuniiddestino')->unsigned()->comment('Identificador del municipio de destino de la encomienda'); 
            $table->string('tipencid', 2)->comment('Identificador del tipo encomienda');
            $table->string('tiesenid', 2)->comment('Identificador del tipo estado encomienda');
            $table->year('encoanio', 4)->comment('Año en el cual se registra la encomienda');
            $table->string('encoconsecutivo', 4)->comment('Consecutivo de la encomienda asignado por cada año');
            $table->dateTime('encofechahoraregistro')->comment('Fecha y hora actual en el que se registra la encomienda');
            $table->string('encocontenido', 1000)->comment('Descripción del contenido de la encomienda');
            $table->decimal('encocantidad', 4, 0)->comment('Cantidad de elemento en la encomienda');
            $table->decimal('encovalordeclarado', 10, 0)->comment('Valor declarado en la encomienda');
            $table->decimal('encovalorenvio', 10, 0)->comment('Valor del envío de la encomienda');
            $table->decimal('encovalordomicilio', 10, 0)->nullable()->comment('Valor del domicilio de la encomienda');
            $table->decimal('encovalorcomisionseguro', 10, 0)->comment('Valor de comisión del seguro de la encomienda');
            $table->decimal('encovalorcomisionempresa', 10, 0)->comment('Valor de comisión para la empresa sobre la encomienda');
            $table->decimal('encovalorcomisionagencia', 10, 0)->comment('Valor de comisión para la agencia que envía la encomienda');
            $table->decimal('encovalorcomisionvehiculo', 10, 0)->comment('Valor de comisión para el vehículo que transporta la encomienda');
            $table->decimal('encovalortotal', 10, 0)->comment('Valor total de la encomienda');
            $table->string('encoobservacion', 500)->nullable()->comment('Observacion de la encomienda');
            $table->date('encofecharecibido')->nullable()->comment('Fecha de recibido de la encomienda');
            $table->boolean('encopagocontraentrega')->default(false)->comment('Determina si la encomienda debe ser pagada contra entrega');
            $table->boolean('encocontabilizada')->default(false)->comment('Determina si la encomienda fue contabilizada');
            $table->timestamps();
            $table->unique(['agenid','encoanio','encoconsecutivo'],'uk_encomienda');
            $table->foreign('agenid')->references('agenid')->on('agencia')->onUpdate('cascade')->index('fk_agenenco');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuaenco');
            $table->foreign('plarutid')->references('plarutid')->on('planillaruta')->onUpdate('cascade')->index('fk_plarutenco');
            $table->foreign('perseridremitente')->references('perserid')->on('personaservicio')->onUpdate('cascade')->index('fk_perserencoremitente');
            $table->foreign('perseriddestino')->references('perserid')->on('personaservicio')->onUpdate('cascade')->index('fk_perserencodestino');
            $table->foreign('encodepaidorigen')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_depaencoorigen');
            $table->foreign('encomuniidorigen')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_muniencoorigen');
            $table->foreign('encodepaiddestino')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_depaencodestino');
            $table->foreign('encomuniiddestino')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_muniencodestino');
            $table->foreign('tipencid')->references('tipencid')->on('tipoencomienda')->onUpdate('cascade')->index('fk_tipencenco');
            $table->foreign('tiesenid')->references('tiesenid')->on('tipoestadoencomienda')->onUpdate('cascade')->index('fk_tiesenenco');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encomienda');
    }
};
