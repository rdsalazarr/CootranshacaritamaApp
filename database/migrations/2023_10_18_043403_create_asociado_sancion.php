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
        Schema::create('asociadosancion', function (Blueprint $table) {
            $table->bigIncrements('asosanid')->unsigned()->comment('Identificador de la tabla asociado sanción');
            $table->integer('asocid')->unsigned()->comment('Identificador del asociado');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que sanciona el asociado');
            $table->smallInteger('tipsanid')->unsigned()->comment('Identificador del tipo de sanción');
            $table->dateTime('asosanfechahora')->comment('Fecha y hora en la cual se crea el registro de la sanción del asociado');
            $table->date('asosanfechamaximapago')->comment('Fecha máxima de pago de la sanción del asociado');
            $table->string('asosanmotivo', 500)->comment('Motivo de la sanción del asociado');
            $table->decimal('asosanvalorsancion', 8, 0)->nullable()->comment('Valor de la sanción apliacada al asociado');
            $table->boolean('asosanprocesada')->default(false)->comment('Determina si la sanción del asociado ha sido procesada'); 
            $table->timestamps();
            $table->foreign('asocid')->references('asocid')->on('asociado')->onUpdate('cascade')->index('fk_asocasosan');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuaasosan');
            $table->foreign('tipsanid')->references('tipsanid')->on('tiposancion')->onUpdate('cascade')->index('fk_tipsanasosan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asociadosancion');
    }
};
