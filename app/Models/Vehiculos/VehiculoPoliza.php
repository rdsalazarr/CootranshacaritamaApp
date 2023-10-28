<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoPoliza extends Model
{
    use HasFactory;

    protected $table      = 'vehiculopoliza';
    protected $primaryKey = 'vehpolid';
    protected $fillable   = ['vehiid','vehpolnumeropolizacontractual','vehpolnumeropolizaextcontrac','vehpolfechainicial', 'vehpolfechafinal',
                             'vehpolextension', 'vehpolnombrearchivooriginal', 'vehpolnombrearchivoeditado', 'vehpolrutaarchivo'];
}