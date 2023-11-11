<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratoServicioEspecial extends Model
{
    use HasFactory;

    protected $table      = 'contratoservicioespecial';
    protected $primaryKey = 'coseesid';
    protected $fillable   = ['persidgerente','ticoseid','ticossid','coseesfechahora','coseesanio','coseesconsecutivo','coseesfechaincial',
                            'coseesorigen','coseesdestino','coseesdescripcionrecorrido','coseesobservacion'];
}
