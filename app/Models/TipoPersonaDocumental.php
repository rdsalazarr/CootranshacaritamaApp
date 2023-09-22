<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPersonaDocumental extends Model
{
    use HasFactory;

    protected $table      = 'tipopersonadocumental';
    protected $primaryKey = 'tipedoid';
    protected $fillable   = ['tipedonombre','tipedoactivo'];
}