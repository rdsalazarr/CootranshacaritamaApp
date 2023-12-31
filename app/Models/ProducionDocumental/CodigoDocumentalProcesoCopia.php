<?php

namespace App\Models\ProducionDocumental;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoCopia extends Model
{
    use HasFactory;

    protected $table      = 'coddocumprocesocopia';
	protected $primaryKey = 'codoppid';
    protected $fillable   = ['codoprid', 'depeid', 'codoppescopiadocumento','codoppfechacompartido','codoppfechaleido'];
}