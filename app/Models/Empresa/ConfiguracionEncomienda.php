<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionEncomienda extends Model
{
    use HasFactory;

    protected $table      = 'configuracionencomienda';
    protected $primaryKey = 'conencid';
    protected $fillable   = ['conencvalorminimoenvio','conencporcentajeseguro', 'conencporcencomisionempresa',
                             'conencporcencomisionagencia', 'conencporcencomisionvehiculo'];
}