<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProceso extends Model
{
    use HasFactory;

    protected $table      = 'codigodocumentalproceso';	 
	protected $primaryKey = 'codoprid';
    protected $fillable   = ['coddocid', 'tiesdoid','codoprfecha','codoprnombredirigido','codoprcargonombredirigido',
                            'codoprasunto','codoprcorreo','codoprcontenido','codoprtieneanexo','codoprtienecopia','codoprsolicitafirma',
                            'codopranexonombre','codoprcopianombre'];
}