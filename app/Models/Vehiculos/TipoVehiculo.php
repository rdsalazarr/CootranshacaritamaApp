<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    use HasFactory;

    protected $table      = 'tipovehiculo';
    protected $primaryKey = 'tipvehid';
    protected $fillable   = ['tipvehnombre','tipvehreferencia','tipvecapacidad','tipvenumerofilas','tipvenumerocolumnas','tipveclasecss','tipvehactivo'];
}