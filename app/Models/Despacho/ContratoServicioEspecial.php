<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratoServicioEspecial extends Model
{
    use HasFactory;

    protected $table      = 'contratoservicioespecial';
    protected $primaryKey = 'coseesid';
    protected $fillable   = ['pecoseid','persidgerente','ticoseid','ticossid','coseesfechahora','coseesanio','coseesconsecutivo','coseesfechaincial','coseesfechafinal',
                            'coseesvalorcontrato','coseesorigen','coseesdestino','coseesdescripcionrecorrido','coseesnombreuniontemporal','coseesobservacion'];
}
