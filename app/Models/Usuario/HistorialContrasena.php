<?php

namespace App\Models\Usuario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialContrasena extends Model
{
    use HasFactory;

    protected $table      = 'historialcontrasena';
	protected $primaryKey = 'hisconid';
    protected $fillable   = ['usuaid','hisconpassword'];
}