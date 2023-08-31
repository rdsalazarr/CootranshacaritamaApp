<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table      = 'rol';    
    protected $primaryKey = 'rolid';
    protected $fillable   = ['rolnombre','rolactivo'];

    //Para realizar la relacion entre rol funcionalidad
    public function funcionalidades(){
        return $this->hasMany('App\Models\RolFuncionalidad', 'rolfunrolid', 'rolid');
    } 
}
