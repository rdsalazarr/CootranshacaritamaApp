<?php

namespace App\Models\Archivo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoDigitalizado extends Model
{
    use HasFactory;

    protected $table      = 'archivohistoricodigitalizado';	 
	protected $primaryKey = 'arhidiid';
    protected $fillable   = ['archisid', 'arhidinombrearchivooriginal','arhidinombrearchivoeditado', 'arhidirutaarchivo'];
}