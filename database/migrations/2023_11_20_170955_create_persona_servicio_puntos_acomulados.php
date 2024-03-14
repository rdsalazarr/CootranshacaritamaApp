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
        Schema::create('personaserpuntosacomulados', function (Blueprint $table) {
            $table->bigIncrements('pesepaid')->unsigned()->comment('Identificador de la tabla persona servicio puntos acomulados');
            $table->integer('perserid')->unsigned()->comment('Identificador de la persona que utiliza el servicio');
            $table->decimal('pesepavalorredimido', 9, 0)->comment('Valor de los punto de la redención');
            $table->smallInteger('usuaid')->unsigned()->nullable()->comment('Identificador del usuario que marca el pago de la redención del valor de los puntos');
            $table->dateTime('pesepafechahorapagado')->nullable()->comment('Fecha y hora actual se realiza el pago de la redención de punto');
            $table->boolean('pesepapagado')->default(false)->comment('Determina si la redención de punto ha sido pagada'); 
            $table->timestamps();
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuapesepa');
            $table->foreign('perserid')->references('perserid')->on('personaservicio')->onUpdate('cascade')->index('fk_perserpesepa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personaserpuntosacomulados');
    }
};
