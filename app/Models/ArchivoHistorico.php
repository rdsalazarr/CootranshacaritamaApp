<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivoHistorico extends Model
{
    use HasFactory;

    protected $table      = 'archivohistorico';	 
	protected $primaryKey = 'archisid';
    protected $fillable   = ['tipdocid', 'usuaid','tiesarid', 'ticaubid','ticrubid','archisfechahora',
                            'archisfechadocumento','archisnumerofolio','archisasuntodocumento','archistomodocumento',
                            'archiscodigodocumental','archisentidadremitente','archisentidadproductora','archisresumendocumento',
                            'archisobservacion'];
}