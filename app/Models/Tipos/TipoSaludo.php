<?php

namespace App\Models\Tipos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSaludo extends Model
{
    use HasFactory;

    protected $table      = 'tiposaludo';
    protected $primaryKey = 'tipsalid';
    protected $fillable   = ['tipsalnombre','tipsalactivo'];
}