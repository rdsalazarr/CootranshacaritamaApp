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
    protected $fillable   = ['movcajid','usuaid','cajaid','agenid','comconanio','comconconsecutivo',
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
    
    public static function obtenerId($fechaActual, $cajaId = '', $agenciaId = '', $usuarioId = '')
    {
        $caja    = ($cajaId    !== '' ) ? $cajaId    : auth()->user()->cajaid;
        $agencia = ($agenciaId !== '' ) ? $agenciaId : auth()->user()->agenid;
        $usuario = ($usuarioId !== '' ) ? $usuarioId : Auth::id();

        $comprobantecontable = DB::table('comprobantecontable')->select('comconid')
                                            ->whereDate('comconfechahora', $fechaActual)
                                            ->where('cajaid', $caja)
                                            ->where('agenid',  $agencia)
                                            ->where('usuaid', $usuario)
                                            ->where('comconestado', 'A')
                                            ->orderBy('comconid', 'Desc')
                                            ->first();

        return $comprobantecontable->comconid;
    }
}