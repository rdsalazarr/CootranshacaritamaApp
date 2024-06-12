<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB, Auth;

class Tiquete extends Model
{
    use HasFactory;

    protected $table      = 'tiquete';
    protected $primaryKey = 'tiquid';
    protected $fillable   = ['agenid','usuaid','plarutid','perserid','tiqudepaidorigen','tiqumuniidorigen','tiqudepaiddestino','tiqumuniiddestino',
                            'tiquanio','tiquconsecutivo','tiqufechahoraregistro','tiqucantidad','tiquvalortiquete','tiquvalordescuento','tiquvalorseguro',
                            'tiquvalorestampilla','tiquvalorfondoreposicion','tiquvalorfondorecaudo','tiquvalorpuntosredimido','tiquvalortotal','tiqucontabilizado'];

    public static function obtenerConsecutivo($anioActual)
    {
        $consecutivoTiquete = DB::table('tiquete')->select('tiquconsecutivo as consecutivo')
                                ->where('tiquanio', $anioActual)
                                ->where('agenid', auth()->user()->agenid)
                                ->orderBy('tiquid', 'Desc')->first();
            $consecutivo = ($consecutivoTiquete === null) ? 1 : $consecutivoTiquete->consecutivo + 1;
        return str_pad($consecutivo,  4, "0", STR_PAD_LEFT);
    }
}