<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table      = 'vehiculo';
    protected $primaryKey = 'vehiid';
    protected $fillable   = ['asocid','tipvehid','tireveid','timaveid','ticoveid','timoveid','ticaveid','ticovhid','agenid',
                            'tiesveid','vehifechaingreso','vehinumerointerno','vehiplaca','vehimodelo','vehicilindraje',
                            'vehinumeromotor','vehinumerochasis','vehinumeroserie','vehinumeroejes','vehiesmotorregrabado',
                            'vehieschasisregrabado','vehiesserieregrabado','vehirutafoto'];

    public function cambioEstado(){
        return $this->hasMany('App\Models\Vehiculos\vehiculocambioestado', 'vehiid', 'vehiid');
    }
    
    public function contrato(){
        return $this->hasMany('App\Models\Vehiculos\VehiculoContrato', 'vehiid', 'vehiid');
    }

    public function crt(){
        return $this->hasMany('App\Models\Vehiculos\VehiculoCrt', 'vehiid', 'vehiid');
    }

    public function poliza(){
        return $this->hasMany('App\Models\Vehiculos\VehiculoPoliza', 'vehiid', 'vehiid');
    }

    public function responsabilidad(){
        return $this->hasMany('App\Models\Vehiculos\VehiculoResponsabilidad', 'vehiid', 'vehiid');
    }

    public function soat(){
        return $this->hasMany('App\Models\Vehiculos\VehiculoSoat', 'vehiid', 'vehiid');
    }

    public function tarjetaOperacion(){
        return $this->hasMany('App\Models\Vehiculos\VehiculoTarjetaOperacion', 'vehiid', 'vehiid');
    }    
}