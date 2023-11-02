<?php

namespace App\Models\Cartera;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColocacionLiquidacion extends Model
{
    use HasFactory;

    protected $table      = 'colocacionliquidacion';
    protected $primaryKey = 'colliqid';
    protected $fillable   = ['solcreid','colliqnumerocuota','colliqfechavencimiento','colliqvalorcuota',
                            'colliqnumerocomprobante',  'colliqfechapago','colliqvalorpagado', 'colliqsaldocapital',
                            'colliqvalorcapitalpagado', 'colliqvalorinterespagado', 'colliqvalorinteresmora'];

}