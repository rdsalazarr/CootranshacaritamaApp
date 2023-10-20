<?php

namespace App\Models\Radicacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoEntranteCambioEstado extends Model
{
    use HasFactory;

    protected $table      = 'radicaciondocentcambioestado';
    protected $primaryKey = 'radeceid';
    protected $fillable   = ['radoenid','tierdeid','radeceusuaid','radecefechahora','radeceobservacion'];
}