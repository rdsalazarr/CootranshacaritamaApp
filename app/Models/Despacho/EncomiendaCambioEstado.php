<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncomiendaCambioEstado extends Model
{
    use HasFactory;

    protected $table      = 'encomiendacambioestado';
    protected $primaryKey = 'encaesid';
    protected $fillable   = ['encoid','tiesenid','encaesusuaid','encaesfechahora','encaesobservacion'];
}