<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    use HasFactory;

    protected $table      = 'dependencia';
    protected $primaryKey = 'depeid';
    protected $fillable   = ['depejefeid','depecodigo','depesigla','depenombre','depecorreo','depeactiva'];

      //Para realizar la relacion entre DependenciaPersona
      public function dependenciaPersona(){
        return $this->hasMany('App\Models\DependenciaPersona', 'depperdepeid', 'depeid');
    } 

    //Para realizar la relacion entre DependenciaSubSerieDocumental
    public function dependenciaSubSerieDocumental(){
        return $this->hasMany('App\Models\DependenciaSubSerieDocumental', 'desusddepeid', 'depeid');
    }
}