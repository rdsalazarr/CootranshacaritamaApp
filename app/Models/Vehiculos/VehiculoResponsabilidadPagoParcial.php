<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoResponsabilidadPagoParcial extends Model
{
    use HasFactory;

    protected $table      = 'vehiculoresponpagoparcial';
    protected $primaryKey = 'vereppid';
    protected $fillable   = ['vehiid','agenid','usuaid','vereppvalorpagado', 'vereppfechapagado','vereppprocesado'];
}