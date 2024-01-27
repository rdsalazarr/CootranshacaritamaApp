<?php

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignacionBancaria extends Model
{
    use HasFactory;

    protected $table      = 'consignacionbancaria';
    protected $primaryKey = 'conbanid';
    protected $fillable   = ['entfinid','usuaid','agenid','conbanfechahora','conbanmonto','conbandescripcion'];
}