<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoCertificado extends Model
{
    use HasFactory;

    protected $table      = 'coddocumprocesocertificado';
	protected $primaryKey = 'codopcid';
    protected $fillable   = ['codoprid', 'tipedoid', 'usuaid', 'codopcconsecutivo', 'codopcsigla',
    					   'codopcanio', 'codopctitulo',  'codopccontenidoinicial'];
}