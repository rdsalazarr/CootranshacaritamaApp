<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVehiculoDistribucion extends Model
{
    use HasFactory;

    protected $table      = 'tipovehiculodistribucion';
    protected $primaryKey = 'tivediid';
    protected $fillable   = ['tipvehid','tivedinumero'];
}