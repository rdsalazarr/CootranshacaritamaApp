<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformacionConfiguracionCorreo extends Model
{
    use HasFactory;

    protected $table      = 'informacionconfiguracioncorreo';
    protected $primaryKey = 'incocoid';
    protected $fillable   = ['incocohost','incocousuario','incococlave','incococlaveapi','incocopuerto'];
}