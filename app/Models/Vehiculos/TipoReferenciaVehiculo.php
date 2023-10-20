<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoReferenciaVehiculo extends Model
{
    use HasFactory;

    protected $table      = 'tiporeferenciavehiculo';
    protected $primaryKey = 'tireveid';
    protected $fillable   = ['tirevenombre','tireveactivo'];
}