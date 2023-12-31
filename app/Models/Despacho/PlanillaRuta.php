<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanillaRuta extends Model
{
    use HasFactory;

    protected $table      = 'planillaruta';
    protected $primaryKey = 'plarutid';
    protected $fillable   = ['agenid','rutaid','vehiid','condid','usuaidregistra','usuaiddespacha','plarutfechahoraregistro',
                            'plarutanio','plarutconsecutivo','plarutfechahorasalida','plarutfechallegadaaldestino','plarutdespachada'];    
}