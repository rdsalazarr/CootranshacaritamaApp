<?php

namespace App\Models\Informacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacionCorreo extends Model
{
    use HasFactory;

    protected $table      = 'informacionnotificacioncorreo';
	protected $primaryKey = 'innocoid';
    protected $fillable   = ['innoconombre','innocoasunto','innococontenido','innocoenviarpiepagina','innocoenviarcopia'];
}