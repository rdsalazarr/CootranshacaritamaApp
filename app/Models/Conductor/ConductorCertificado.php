<?php

namespace App\Models\Conductor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConductorCertificado extends Model
{
    use HasFactory;

    protected $table      = 'conductorcertificado';
    protected $primaryKey = 'concerid';
    protected $fillable   = ['condid','concerextension','concernombrearchivooriginal','concernombrearchivoeditado','concerrutaarchivo'];
}