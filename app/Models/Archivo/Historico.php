<?php

namespace App\Models\Archivo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historico extends Model
{
    use HasFactory;

    protected $table      = 'archivohistorico';	 
	protected $primaryKey = 'archisid';
    protected $fillable   = ['tipdocid', 'usuaid','tiesarid', 'ticaubid','ticrubid','archisfechahora',
                            'archisfechadocumento','archisnumerofolio','archisasuntodocumento','archistomodocumento',
                            'archiscodigodocumental','archisentidadremitente','archisentidadproductora','archisresumendocumento',
                            'archisobservacion'];
}