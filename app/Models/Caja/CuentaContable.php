<?php

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class CuentaContable extends Model
{
    use HasFactory;

    protected $table      = 'cuentacontable';
    protected $primaryKey = 'cueconid';
    protected $fillable   = ['cueconnombre','cuecondescripcion','cueconnaturaleza','cueconcodigo','cueconactiva'];
    
    public static function consultarId($nombre)
    {         
        $cuentacontable = DB::table('cuentacontable')->select('cueconid')->where('cueconnombre', $nombre)->first();

        return $cuentacontable->cueconid;
    }
}