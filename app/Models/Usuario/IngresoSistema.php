<?php

namespace App\Models\Usuario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngresoSistema extends Model
{
    use HasFactory;

    protected $table      = 'ingresosistema';
	protected $primaryKey = 'ingsisid';
    protected $fillable   = ['usuaid','ingsisipacceso','ingsisfechahoraingreso','ingsisfechahorasalida'];
}
