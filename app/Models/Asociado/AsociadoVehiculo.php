<?php

namespace App\Models\Asociado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsociadoVehiculo extends Model
{
    use HasFactory;

    protected $table      = 'asociadovehiculo';
    protected $primaryKey = 'asovehid';
    protected $fillable   = ['asocid','vehiid'];
}