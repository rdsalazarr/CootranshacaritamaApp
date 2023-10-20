<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCarroceriaVehiculo extends Model
{
    use HasFactory;

    protected $table      = 'tipocarroceriavehiculo';
    protected $primaryKey = 'ticaveid';
    protected $fillable   = ['ticavenombre','ticaveactivo'];
}