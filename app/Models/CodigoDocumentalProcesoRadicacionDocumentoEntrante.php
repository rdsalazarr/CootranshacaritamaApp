<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoDocumentalProcesoRadicacionDocumentoEntrante extends Model
{
    use HasFactory;

    protected $table      = 'coddocumprocesoraddocentrante';
    protected $primaryKey = 'cdprdeid';
    protected $fillable   = ['codoprid','radoenid'];
}