<?php

namespace App\Models\Tipos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDespedida extends Model
{
    use HasFactory;

    protected $table      = 'tipodespedida';
    protected $primaryKey = 'tipdesid';
    protected $fillable   = ['tipdesnombre','tipdesactivo'];
}