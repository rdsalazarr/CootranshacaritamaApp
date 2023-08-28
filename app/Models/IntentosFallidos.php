<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntentosFallidos extends Model
{
    use HasFactory;

    protected $table      = 'intentosfallidos';
	protected $primaryKey = 'intfalid';
    protected $fillable   = ['intfalusurio','intfalipacceso','intfalfecha'];
}