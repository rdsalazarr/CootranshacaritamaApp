<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encomienda extends Model
{
    use HasFactory;

    protected $table      = 'encomienda';
    protected $primaryKey = 'encoid';
    protected $fillable   = ['agenid','usuaid','plarutid','perseridremitente','perseriddestino','depaidorigen','muniidorigen','depaiddestino','muniiddestino',
                            'tipencid','tiesenid','usuaid','encofechahoraregistro','encocontenido','encocantidad', 'encovalordeclarado','encovalorenvio',
                             'encovalordomicilio','encovalorcomisionseguro','encovalorcomisionvehiculo', 'encovalorcomisionagencia','encovalorcomisionempresa',
                             'encoobservacion','encofecharecibido','encopagada'];
}