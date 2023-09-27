<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadicacionDocumentoEntranteCambioEstado extends Model
{
    use HasFactory;

    protected $table      = 'radicaciondocentcambioestado';
    protected $primaryKey = 'radeceid';
    protected $fillable   = ['radoenid','tierdeid','radeceusuaid','radecefechahora','radeceobservacion'];
}