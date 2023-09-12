<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoCambioEstado extends Model
{
    use HasFactory;

    //public $timestamps    = false;
    protected $table      = 'coddocumprocesocambioestado';	 
	protected $primaryKey = 'codpceid';
    protected $fillable   = ['codoprid', 'tiesdoid','usuaid', 'codpcefechahora','codpceobservacion'];
}