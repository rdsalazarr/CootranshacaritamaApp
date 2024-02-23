<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColocacionLiquidacion extends Model
{
    use HasFactory;

    protected $table      = 'colocacionliquidacion';
    protected $primaryKey = 'colliqid';
    protected $fillable   = ['coloid','colliqnumerocuota','colliqfechavencimiento','colliqvalorcuota',
                            'comconid', 'colliqfechapago','colliqvalorpagado', 'colliqsaldocapital',
                            'colliqvalorcapitalpagado', 'colliqvalorinterespagado', 'colliqvalorinteresmora'];
}