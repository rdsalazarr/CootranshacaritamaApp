<?php

namespace App\Models\Radicacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoEntranteDependencia extends Model
{
    use HasFactory;

    protected $table      = 'radicaciondocentdependencia';
    protected $primaryKey = 'radoedid';
    protected $fillable   = ['radoenid','depeid','radoedsuaid','radoedescopia'];
}