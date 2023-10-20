<?php

namespace App\Models\Tipos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class TipoEstanteArchivador extends Model
{
    use HasFactory;

    protected $table      = 'tipoestantearchivador';
    protected $primaryKey = 'tiesarid';
    protected $fillable   = ['tiesarnombre','tiesaractivo'];
}