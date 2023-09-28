<?php

namespace App\Models;

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