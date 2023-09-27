<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadicacionDocumentoEntranteAnexo extends Model
{
    use HasFactory;

    protected $table      = 'radicaciondocentanexo';
    protected $primaryKey = 'radoeaid';
    protected $fillable   = ['radoenid','radoeanombreanexooriginal','radoeanombreanexoeditado','radoearutaanexo','radoearequiereradicado'];
}