<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonaServicioFidelizacion extends Model
{
    use HasFactory;

    protected $table      = 'personaserviciofidelizacion';
    protected $primaryKey = 'pesefiid';
    protected $fillable   = ['agenid','usuaid','perserid','pesefifechahoraregistro','pesefitipoproceso','pesefinumeropunto',
                            'pesefifechahoraredimido', 'pesefiredimido'];
}