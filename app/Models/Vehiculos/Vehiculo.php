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
}