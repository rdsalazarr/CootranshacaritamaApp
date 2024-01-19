<?php

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprobanteContable extends Model
{
    use HasFactory;

    protected $table      = 'comprobantecontable';
    protected $primaryKey = 'comconid';
    protected $fillable   = ['movcajid','usuaid','cajaid','comconanio','comconconsecutivo',
                            'comconfechahora', 'comcondescripcion','comconfechahoracierre','comconestado'];
}