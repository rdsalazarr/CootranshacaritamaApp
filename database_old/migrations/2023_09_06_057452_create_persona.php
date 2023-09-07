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
        Schema::create('persona', function (Blueprint $table) {
            $table->increments('persid')->index('pk_pers')->comment('Identificador de la tabla persona');
            $table->smallInteger('carlabid')->unsigned()->comment('Identificador del cargo laboral');
            $table->tinyInteger('tipideid')->unsigned()->comment('Identificador del tipo de identificación');
            $table->tinyInteger('tirelaid')->unsigned()->comment('Identificador del tipo de realación laboral');
            $table->string('persdocumento', 10)->comment('Documento de la persona');
            $table->string('persprimernonbre', 40)->comment('Primer nombre de la persona');
            $table->string('perssegundononbre', 40)->nullable()->comment('Segundo nombre de la persona');
            $table->string('persprimerapellido', 40)->comment('Primer apellido de la persona');
            $table->string('perssegundoapellido', 40)->nullable()->comment('Segundo apellido de la persona');
            $table->string('persrutafirma', 100)->nullable()->comment('Ruta de la imagen de la firma de la persona');
            $table->boolean('persactivo')->default(false)->comment('Determina si la persona se encuentra activo');
            $table->timestamps();
            $table->unique(['tipideid','persdocumento'],'uk_personatipoidentificacion');
            $table->foreign('carlabid')->references('carlabid')->on('cargolaboral')->onUpdate('cascade')->index('fk_carglabpers');   
            $table->foreign('tipideid')->references('tipideid')->on('tipoidentificacion')->onUpdate('cascade')->index('fk_tipidepers');
            $table->foreign('tirelaid')->references('tirelaid')->on('tiporelacionlaboral')->onUpdate('cascade')->index('fk_tirelapers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};
