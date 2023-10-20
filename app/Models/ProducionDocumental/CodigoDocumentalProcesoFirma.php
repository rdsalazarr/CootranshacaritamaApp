<?php

namespace App\Models\ProducionDocumental;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoFirma extends Model
{
    use HasFactory;

    protected $table      = 'coddocumprocesofirma';
    protected $primaryKey = 'codopfid';
    protected $fillable   = ['codoprid', 'persid','carlabid','codopftoken','codopffechahorafirmado', 'codopffechahoranotificacion',
                            'codopffechahoramaxvalidez','codopfmensajecorreo','codopfmensajecelular','codopffirmado','codopfesinvitado'];
}