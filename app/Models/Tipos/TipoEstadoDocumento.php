<?php

namespace App\Models\Tipos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEstadoDocumento extends Model
{
    use HasFactory;

    protected $table      = 'tipoestadodocumento';
    public $timestamps    = false;
    protected $primaryKey = 'tiesdoid';
    protected $fillable   = ['tiesdonombre'];
}