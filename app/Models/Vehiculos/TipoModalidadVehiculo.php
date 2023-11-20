<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoModalidadVehiculo extends Model
{
    use HasFactory;

    protected $table      = 'tipomodalidadvehiculo';
	protected $primaryKey = 'timoveid';
    protected $fillable   = ['timovenombre','timovecuotasostenimiento','timovedescuentopagoanticipado','timoverecargomora','timovetienedespacho'];
}