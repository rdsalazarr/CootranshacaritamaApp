<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadicacionDocumentoEntrante extends Model
{
    use HasFactory;

    protected $table      = 'radicaciondocumentoentrante';
    protected $primaryKey = 'radoenid';
    protected $fillable   = ['peradoid','tipmedid','tierdeid','radoenusuaid','radoenconsecutivo','radoenanio',
                            'radoenfechahoraradicado','radoenfechadocumento','radoenfechallegada','radoenpersonaremitente',
                            'radoenasunto','radoentieneanexo','radoendescripcionanexo','radoenobservacion'];
}