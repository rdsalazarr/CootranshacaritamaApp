<?php

namespace App\Models\AtencionUsuario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table      = 'solicitud';
    protected $primaryKey = 'soliid';
    protected $fillable   = ['peradoid','radoenid','tipsolid','timesoid','vehiid','condid',
                            'solifechahoraregistro','solifechahoraincidente','solimotivo','soliobservacion',
                            'soliradicado','solinombreanexooriginal','solinombreanexoeditado','solirutaanexo'];
}