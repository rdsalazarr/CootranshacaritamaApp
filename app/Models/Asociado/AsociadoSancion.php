<?php

namespace App\Models\Asociado;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsociadoSancion extends Model
{
    use HasFactory;

    protected $table      = 'asociadosancion';
    protected $primaryKey = 'asosanid';
    protected $fillable   = ['asocid','usuaid','tipsanid', 'asosanfechahora', 'asosanfechamaximapago', 
                            'asosanmotivo', 'asosanvalorsancion', 'asosanprocesada'];
}