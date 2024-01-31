<?php

namespace App\Models\Tipos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcesoAutomatico extends Model
{
    use HasFactory;

    protected $table      = 'procesoautomatico';
    protected $primaryKey = 'proautid';
    protected $fillable   = ['proautnombre','proautfechaejecucion','proauttipo'];
}