<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colocacion extends Model
{
    use HasFactory;

    protected $table      = 'colocacion';
    protected $primaryKey = 'coloid';
    protected $fillable   = ['usuaid','solcreid','tiesclid','colofechahoraregistro','colofechadesembolso','coloanio',
                            'colonumerodesembolso', 'colovalordesembolsado', 'colotasa','colonumerocuota'];
}