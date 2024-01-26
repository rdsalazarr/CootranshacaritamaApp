<?php

namespace App\Models\Tipos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntidadFinanciera extends Model
{
    use HasFactory;

    protected $table      = 'entidadfinanciera';
    protected $primaryKey = 'entfinid';
    protected $fillable   = ['entfinnombre', 'entfinnumerocuenta', 'entfinactiva'];
}