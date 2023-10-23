<?php

namespace App\Models\Conductor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConductorCambioEstado extends Model
{
    use HasFactory;

    protected $table      = 'conductorcambioestado';
    protected $primaryKey = 'cocaesid';
    protected $fillable   = ['asocid','tiescoid','cocaesusuaid','cocaesfechahora','cocaesobservacion'];
}