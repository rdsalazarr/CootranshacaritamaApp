<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoCambioEstado extends Model
{
    use HasFactory;

    protected $table      = 'vehiculocambioestado';
    protected $primaryKey = 'vecaesid';
    protected $fillable   = ['vehiid','tiesveid','vecaesusuaid','vecaesfechahora','vecaesobservacion'];
}