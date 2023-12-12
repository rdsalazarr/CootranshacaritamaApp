<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoSuspendido extends Model
{
    use HasFactory;

    protected $table      = 'vehiculosuspendido';
    protected $primaryKey = 'vehsusid';
    protected $fillable   = ['vehiid','usuaid','vehsusfechahora', 'vehsusfechainicialsuspencion','vehsusfechafinalsuspencion', 
                             'vehsusmotivo', 'vehsusprocesada'];
}