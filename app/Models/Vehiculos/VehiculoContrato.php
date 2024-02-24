<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoContrato extends Model
{
    use HasFactory;

    protected $table      = 'vehiculocontrato';
    protected $primaryKey = 'vehconid';
    protected $fillable   = ['vehiid','asocid','persidgerente','vehconanio','vehconnumero','vehconfechainicial','vehconfechafinal','vehconobservacion'];

    public function firma(){
        return $this->hasMany('App\Models\Vehiculos\VehiculoContratoFirma', 'vehconid', 'vehconid');
    }
}