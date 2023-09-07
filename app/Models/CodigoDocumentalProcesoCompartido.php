<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoCompartido extends Model
{
    use HasFactory;

    protected $table      = 'coddocumprocesocompartido';	 
	protected $primaryKey = 'codopdid';
    protected $fillable   = ['codoprid', 'usuaid','codopdfechacompartido', 'codopdfechaleido'];
}