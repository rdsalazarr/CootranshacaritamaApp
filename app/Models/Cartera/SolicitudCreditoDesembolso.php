<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCreditoDesembolso extends Model
{
    use HasFactory;

    protected $table      = 'solicitudcreditodesembolso';
    protected $primaryKey = 'socrdeid';
    protected $fillable   = ['solcreid','socrdefechadesembolso','socrdeanio','socrdenumerodesembolso','socrdevalordesembolsado',
                            'socrdetasa','socrdenumerocuota',
                            'solcredescripcion', 'solcrenumerocuota'];
}