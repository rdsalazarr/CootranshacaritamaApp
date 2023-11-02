<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColocacionCambioEstado extends Model
{
    use HasFactory;

    protected $table      = 'colocacioncambioestado';
    protected $primaryKey = 'cocaesid';
    protected $fillable   = ['coloid','tiesclid','cocaesusuaid','cocaesfechahora','cocaesobservacion'];
}