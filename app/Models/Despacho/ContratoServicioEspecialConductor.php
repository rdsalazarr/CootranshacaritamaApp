<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratoServicioEspecialConductor extends Model
{
    use HasFactory;

    protected $table      = 'contratoservicioespecialcond';
    protected $primaryKey = 'coseecod';
    protected $fillable   = ['coseesid','condid'];
}