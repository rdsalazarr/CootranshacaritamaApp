<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoContratoFirma extends Model
{
    use HasFactory;

    protected $table      = 'vehiculocontratofirma';
    protected $primaryKey = 'vecofiid';
    protected $fillable   = ['vehconid','persid','vecofitoken','vecofiipacceso','vecofifechahorafirmado','vecofifechahoranotificacion',
                            'vecofifechahoramaxvalidez','vecofimensajecorreo','vecofimensajecelular','vecofifirmado'];

}