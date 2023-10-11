<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RadicacionDocumentoEntranteDependencia extends Model
{
    use HasFactory;

    protected $table      = 'radicaciondocentdependencia';
    protected $primaryKey = 'radoedid';
    protected $fillable   = ['radoenid','depeid','radoedsuaid','radoedescopia'];
}