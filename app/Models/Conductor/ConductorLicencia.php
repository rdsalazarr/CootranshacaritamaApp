<?php

namespace App\Models\Conductor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConductorLicencia extends Model
{
    use HasFactory;

    protected $table      = 'conductorlicencia';
    protected $primaryKey = 'conlicid';
    protected $fillable   = ['condid','ticaliid','conlicnumero','conlicfechaexpedicion','conlicfechavencimiento','conlicextension',
                            'conlicnombrearchivooriginal', 'conlicnombrearchivoeditado', 'conlicrutaarchivo'];
}