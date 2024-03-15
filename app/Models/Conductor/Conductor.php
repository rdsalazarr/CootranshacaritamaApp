<?php

namespace App\Models\Conductor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    use HasFactory;

    protected $table      = 'conductor';
    protected $primaryKey = 'condid';
    protected $fillable   = ['persid','tiescoid','tipconid','agenid','condfechaingreso'];

    //Para realizar la relacion con cambio estado
    public function conductorCambioEstado(){
        return $this->hasMany('App\Models\Conductor\ConductorCambioEstado', 'condid', 'condid');
    }

    //Para realizar la relacion con las licencias
    public function conductorLicencia(){
        return $this->hasMany('App\Models\Conductor\ConductorLicencia', 'condid', 'condid');
    }

    //Para realizar la relacion con las certificado
    public function conductorCertificado(){
        return $this->hasMany('App\Models\Conductor\ConductorCertificado', 'condid', 'condid');
    }
}