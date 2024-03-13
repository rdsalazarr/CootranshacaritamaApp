<?php

namespace App\Models\Empresa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FidelizacionCliente extends Model
{
    use HasFactory;

    protected $table      = 'fidelizacioncliente';
    protected $primaryKey = 'fidcliid';
    protected $fillable   = ['fidclivalorfidelizacion','fidclivalorpunto','fidclipuntosminimoredimir'];
}
