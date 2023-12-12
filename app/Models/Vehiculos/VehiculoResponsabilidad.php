<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoResponsabilidad extends Model
{
    use HasFactory;

    protected $table      = 'vehiculoresponsabilidad';
    protected $primaryKey = 'vehresid';
    protected $fillable   = ['vehiid','vehresfechapago','vehresvalorresponsabilidad','vehresfechapagado', 'vehresvalorpagado',
                             'agenid', 'usuaid'];
}