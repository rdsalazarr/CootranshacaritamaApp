<?php

namespace App\Models\ProducionDocumental;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoCitacion extends Model
{
    use HasFactory;

    protected $table      = 'coddocumprocesocitacion';	 
	protected $primaryKey = 'codoptid';
    protected $fillable   = ['codoprid','usuaid','tipactid','codoptconsecutivo','codoptsigla','codoptanio',
							 'codopthora','codoptlugar','codoptordendeldia','codoptfecharealizacion'];
}
