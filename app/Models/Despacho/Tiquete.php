<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiquete extends Model
{
    use HasFactory;

    protected $table      = 'tiquete';
    protected $primaryKey = 'tiquid';
    protected $fillable   = ['agenid','usuaid','plarutid','perserid','depaidorigen','muniidorigen','depaiddestino','muniiddestino',
                            'tiquanio','tiquconsecutivo','tiqufechahoraregistro','tiqucantidad','tiquvalortiquete','tiquvalordescuento', 
                            'tiquvalorestampilla','tiquvalorfondoreposicion','tiquvalortotal','tiqucontabilizada'];
}