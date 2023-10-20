<?php

namespace App\Models\Informacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionCorreo extends Model
{
    use HasFactory;

    protected $table      = 'informacionconfiguracioncorreo';
    protected $primaryKey = 'incocoid';
    protected $fillable   = ['incocohost','incocousuario','incococlave','incococlaveapi','incocopuerto'];
}