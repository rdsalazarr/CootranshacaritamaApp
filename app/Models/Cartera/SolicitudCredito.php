<?php

namespace App\Models○\Cartera;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCredito extends Model
{
    use HasFactory;

    protected $table      = 'solicitudcredito';
    protected $primaryKey = 'solcreid';
    protected $fillable   = ['usuaid','lincreid','asocid','tiesscid','solcrefecharegistro','solcredescripcion',
                            'solcremonto','solcretasa','solcrenumerocuota'];
}