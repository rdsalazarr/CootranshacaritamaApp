<?php

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB, Auth;

class ComprobanteContable extends Model
{
    use HasFactory;

    protected $table      = 'comprobantecontable';
    protected $primaryKey = 'comconid';
    protected $fillable   = ['movcajid','usuaid','cajaid','comconanio','comconconsecutivo',
                            'comconfechahora', 'comcondescripcion','comconfechahoracierre','comconestado'];

    public static function obtenerConsecutivo($anioActual)
	{
        $consecutivoComprobanteContable = DB::table('comprobantecontable')->select('comconconsecutivo as consecutivo')
                                                        ->where('comconanio', $anioActual)
                                                        ->where('agenid', auth()->user()->agenid)
                                                        ->orderBy('comconid', 'Desc')->first();
        $consecutivo = ($consecutivoComprobanteContable === null) ? 1 : $consecutivoComprobanteContable->consecutivo + 1;
        return str_pad($consecutivo,  5, "0", STR_PAD_LEFT);
    }

    public static function obtenerId($fechaActual)
    {
        $comprobantecontable = DB::table('comprobantecontable')->select('comconid')
                                            ->whereDate('comconfechahora', $fechaActual)
                                            ->where('cajaid', auth()->user()->cajaid)
                                            ->where('agenid', auth()->user()->agenid)
                                            ->where('usuaid', Auth::id())
                                            ->where('comconestado', 'A')
                                            ->orderBy('comconid', 'Desc')
                                            ->first();

        return $comprobantecontable->comconid;
    }
}