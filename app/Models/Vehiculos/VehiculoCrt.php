<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoCrt extends Model
{
    use HasFactory;

    protected $table      = 'vehiculocrt';
    protected $primaryKey = 'vehcrtid';
    protected $fillable   = ['vehiid','vehcrtnumero','vehcrtfechainicial','vehcrtfechafinal', 'vehcrtextension',
                                'vehcrtnombrearchivooriginal', 'vehcrtnombrearchivoeditado', 'vehcrtrutaarchivo'];
}