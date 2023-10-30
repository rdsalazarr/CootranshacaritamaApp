<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineaCredito extends Model
{
    use HasFactory;

    protected $table      = 'lineacredito';
    protected $primaryKey = 'lincreid';
    protected $fillable   = ['lincrenombre', 'lincreporcentaje','lincreactiva'];
}