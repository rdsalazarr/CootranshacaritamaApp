<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $table      = 'ruta';
    protected $primaryKey = 'rutaid';
    protected $fillable   = ['depaidorigen','muniidorigen','depaiddestino','muniiddestino','rutavalorestampilla','rutatienenodos','rutaactiva'];

    //Para realizar la relacion con los nodos
    public function rutaNodos(){
        return $this->hasMany('App\Models\Despacho\RutaNodo', 'rutaid', 'rutaid');
    }

    //Para realizar la relacion con la tarifa de tiquete
    public function tarifaTiquete(){
        return $this->hasMany('App\Models\Despacho\TarifaTiquete', 'rutaid', 'rutaid');
    }
}