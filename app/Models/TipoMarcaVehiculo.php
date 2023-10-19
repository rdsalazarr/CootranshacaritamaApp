<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMarcaVehiculo extends Model
{
    use HasFactory;

    protected $table      = 'tipomarcavehiculo';
    protected $primaryKey = 'timaveid';
    protected $fillable   = ['timavenombre','timaveactiva'];
}