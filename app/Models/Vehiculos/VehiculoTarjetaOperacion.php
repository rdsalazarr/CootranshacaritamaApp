<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoTarjetaOperacion extends Model
{
    use HasFactory;

    protected $table      = 'vehiculotarjetaoperacion';
    protected $primaryKey = 'vetaopid';
    protected $fillable   = ['vehiid','tiseveid','vetaopnumero','vetaopfechainicial','vetaopfechafinal','vetaopenteadministrativo',
                            'vetaopradioaccion','vetaopextension','vetaopnombrearchivooriginal', 'vetaopnombrearchivoeditado', 'vetaoprutaarchivo'];
}