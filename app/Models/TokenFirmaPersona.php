<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenFirmaPersona extends Model
{
    use HasFactory;

    protected $table      = 'tokenfirmapersona';  
    protected $primaryKey = 'tofipeid';
    protected $fillable   = ['persid','tofipetoken','tofipefechahoranotificacion','tofipefechahoramaxvalidez',
                             'tofipemsjcorreo','tofipemsjcelular','tofipeutilizado'];
}