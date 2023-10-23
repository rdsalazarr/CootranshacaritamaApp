<?php

namespace App\Models\Asociado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsociadoCambioEstado extends Model
{
    use HasFactory;

    protected $table      = 'asociadocambioestado';
    protected $primaryKey = 'ascaesid';
    protected $fillable   = ['asocid','tiesasid','ascaesusuaid','ascaesfechahora','ascaesobservacion'];
}