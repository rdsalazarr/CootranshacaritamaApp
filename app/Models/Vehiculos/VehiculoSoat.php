<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoSoat extends Model
{
    use HasFactory;

    protected $table      = 'vehiculosoat';
    protected $primaryKey = 'vehsoaid';
    protected $fillable   = ['vehiid','vehpolnumeropolizacontractual','vehpolnumeropolizaextcontrac','vehpolfechainicial', 'vehpolfechafinal',
                             'vehpolextension', 'vehpolnombrearchivooriginal', 'vehpolnombrearchivoeditado', 'vehpolrutaarchivo'];
}