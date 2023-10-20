<?php

namespace App\Models\DatosGeograficos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $table      = 'departamento';
    protected $primaryKey = 'depaid';
    protected $fillable   = ['depacodigo','depanombre','depahacepresencia'];
}