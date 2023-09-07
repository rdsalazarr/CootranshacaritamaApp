<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoRadicado extends Model
{
    use HasFactory;

    protected $table      = 'coddocumprocesoradicado';
	protected $primaryKey = 'codpraid';
    protected $fillable   = ['codoprid', 'usuaid','codpraconsecutivo', 'codpraanio','codprafechahoraradicado'];
}