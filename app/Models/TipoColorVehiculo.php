<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoColorVehiculo extends Model
{
    use HasFactory;

    protected $table      = 'tipocolorvehiculo';
    protected $primaryKey = 'ticoveid';
    protected $fillable   = ['ticovenombre','ticoveactivo'];
}