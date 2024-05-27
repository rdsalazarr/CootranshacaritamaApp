<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class VehiculoContrato extends Model
{
    use HasFactory;

    protected $table      = 'vehiculocontrato';
    protected $primaryKey = 'vehconid';
    protected $fillable   = ['vehiid','asocid','persidgerente','vehconanio','vehconnumero','vehconfechainicial','vehconfechafinal','vehconobservacion'];

    public function firma(){
        return $this->hasMany('App\Models\Vehiculos\VehiculoContratoFirma', 'vehconid', 'vehconid');
    }

    public static function obtenerConsecutivoContrato($anioActual)
	{
        $consecutivoContrato = DB::table('vehiculocontrato')->select('vehconnumero as consecutivo')
								->where('vehconanio', $anioActual)->orderBy('vehconid', 'desc')->first();

        $consecutivo = ($consecutivoContrato === null) ? 1 : $consecutivoContrato->consecutivo + 1;

        return str_pad($consecutivo,  4, "0", STR_PAD_LEFT);
    }
}