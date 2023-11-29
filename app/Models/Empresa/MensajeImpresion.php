<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MensajeImpresion extends Model
{
    use HasFactory;

    protected $table      = 'mensajeimpresion';
    protected $primaryKey = 'menimpid';
    protected $fillable   = ['menimpnombre','menimpvalor'];
}