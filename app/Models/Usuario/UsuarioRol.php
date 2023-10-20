<?php

namespace App\Models\Usuario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioRol extends Model
{
    use HasFactory;

    public $timestamps    = false;
    protected $table      = 'usuariorol';
    protected $primaryKey = 'usurolid';
    protected $fillable   = ['usurolusuaid','usurolrolid'];
}