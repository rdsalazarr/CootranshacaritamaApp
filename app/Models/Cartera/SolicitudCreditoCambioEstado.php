<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCreditoCambioEstado extends Model
{
    use HasFactory;

    protected $table      = 'solicitudcreditocambioestado';
    protected $primaryKey = 'socrceid';
    protected $fillable   = ['solcreid','tiesscid','socrceusuaid','socrcefechahora','socrceobservacion'];
}