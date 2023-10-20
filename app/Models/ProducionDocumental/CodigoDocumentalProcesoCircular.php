<?php

namespace App\Models\ProducionDocumental;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoCircular extends Model
{
    use HasFactory;

    protected $table      = 'coddocumprocesocircular';
	protected $primaryKey = 'codoplid';
    protected $fillable   = ['codoprid','tipdesid','usuaid','codoplconsecutivo','codoplsigla','codoplanio'];
}
