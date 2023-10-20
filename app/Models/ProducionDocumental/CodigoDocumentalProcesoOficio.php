<?php

namespace App\Models\ProducionDocumental;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoOficio extends Model
{
    use HasFactory;

    protected $table      = 'coddocumprocesooficio';
	protected $primaryKey = 'codopoid';
    protected $fillable   = ['codoprid', 'usuaid','tipsalid','tipdesid','codopoconsecutivo','codoposigla',
							'codopoanio', 'codopotitulo','codopociudad','codopocargodestinatario','codopoempresa',
                            'codopodireccion','codopotelefono','codoporesponderadicado'];
}