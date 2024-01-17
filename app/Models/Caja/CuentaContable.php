<?php

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaContable extends Model
{
    use HasFactory;

    protected $table      = 'cuentacontable';
    protected $primaryKey = 'cueconid';
    protected $fillable   = ['cueconnombre','cueconnaturaleza','cueconcodigo','cueconactiva'];
}