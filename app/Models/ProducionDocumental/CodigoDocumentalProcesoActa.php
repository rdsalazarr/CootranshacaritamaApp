<?php

namespace App\Models\ProducionDocumental;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoActa extends Model
{
    use HasFactory;

    protected $table      = 'coddocumprocesoacta';	 
	protected $primaryKey = 'codopaid';
    protected $fillable   = ['codoprid', 'tipactid','usuaid', 'codopaconsecutivo', 'codopasigla',
    					    'codopaanio', 'codopahorainicio', 'codopahorafinal', 'codopalugar', 'codopaquorum',
    					    'codopaordendeldia', 'codopainvitado', 'codopaausente', 'codopaconvocatoria', 'codopaconvocatorialugar',
                            'codopaconvocatoriafecha', 'codopaconvocatoriahora'];

}