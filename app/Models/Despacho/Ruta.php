<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $table      = 'ruta';
    protected $primaryKey = 'rutaid';
    protected $fillable   = ['depaidorigen','muniidorigen','depaiddestino','muniiddestino','rutatienenodos','rutaactiva'];
}