<?php

namespace App\Models\Tipos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSancion extends Model
{
    use HasFactory;

    protected $table      = 'tiposancion';
    protected $primaryKey = 'tipsanid';
    protected $fillable   = ['tipsannombre','tipsanactivo'];
}