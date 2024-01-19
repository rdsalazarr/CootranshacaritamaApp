<?php

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprobanteContableDetalle extends Model
{
    use HasFactory;

    protected $table      = 'comprobantecontabledetalle';
    protected $primaryKey = 'cocodeid';
    protected $fillable   = ['comconid','cueconid','cocodefechahora','cocodemonto','cocodecontabilizado'];
}