<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformacionNotificacionCorreo extends Model
{
    use HasFactory;

    protected $table      = 'informacionnotificacioncorreo';
	protected $primaryKey = 'innocoid';
    protected $fillable   = ['innoconombre','innocotitulo','innococontenido','innocoenviarpiepagina','innocoenviarcopia'];
}