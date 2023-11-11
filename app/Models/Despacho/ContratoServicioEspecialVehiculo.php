<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratoServicioEspecialVehiculo extends Model
{
    use HasFactory;

    protected $table      = 'contratoservicioespecialvehi';
    protected $primaryKey = 'coseevid';
    protected $fillable   = ['coseesid','vehiid','coseevextractoanio','coseevextractoconsecutivo'];
}