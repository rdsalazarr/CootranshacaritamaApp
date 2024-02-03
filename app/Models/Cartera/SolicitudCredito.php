<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCredito extends Model
{
    use HasFactory;

    protected $table      = 'solicitudcredito';
    protected $primaryKey = 'solcreid';
    protected $fillable   = ['usuaid','lincreid','persid','vehiid','tiesscid','solcrefechasolicitud','solcredescripcion',
                            'solcrevalorsolicitado','solcretasa','solcrenumerocuota','solcreobservacion'];
}