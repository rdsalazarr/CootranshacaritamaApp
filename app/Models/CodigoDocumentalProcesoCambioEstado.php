<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoCambioEstado extends Model
{
    use HasFactory;

    protected $table      = 'coddocumprocesocambioestado';	 
	protected $primaryKey = 'codpceid';
    protected $fillable   = ['codoprid', 'tiesdoid','codpceusuaid', 'codpcefechahora','codpceobservacion'];
}