<?php

namespace App\Models\Procesos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcesosAutomaticos extends Model
{
    use HasFactory;

    protected $table      = 'procesoautomatico';
    protected $primaryKey = 'proautid';
    protected $fillable   = ['proautnombre','proautclasephp','proautmetodo','proautfechaejecucion','proauttipo'];
}