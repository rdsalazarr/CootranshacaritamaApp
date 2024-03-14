<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonaServicioPuntosAcomulados extends Model
{
    use HasFactory;

    protected $table      = 'personaserpuntosacomulados';
    protected $primaryKey = 'pesepaid';
    protected $fillable   = ['perserid','pesepavalorredimido','usuaid','pesepafechahorapagado','pesepapagado'];
}