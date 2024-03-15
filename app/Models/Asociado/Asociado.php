<?php

namespace App\Models\Asociado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asociado extends Model
{
    use HasFactory;

    protected $table      = 'asociado';
    protected $primaryKey = 'asocid';
    protected $fillable   = ['persid','tiesasid','asocfechaingreso', 'asocfecharetiro','asocrutaarchivo'];
}