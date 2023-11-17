<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoContratoAsocidado extends Model
{
    use HasFactory;

    protected $table      = 'vehiculocontratoasociado';
    protected $primaryKey = 'vecoasid';
    protected $fillable   = ['vehconid','asocid'];
}