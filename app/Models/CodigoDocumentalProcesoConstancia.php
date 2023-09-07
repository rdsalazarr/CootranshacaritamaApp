<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoConstancia extends Model
{
    use HasFactory;

    protected $table      = 'coddocumprocesoconstancia';	 
	protected $primaryKey = 'codopnid';
    protected $fillable   = ['codoprid', 'tipedoid', 'usuaid', 'codopnconsecutivo', 'codopnsigla',
    					    'codopnanio', 'codopntitulo', 'codopnnombredirigido', 'codopncontenidoinicial'];
}