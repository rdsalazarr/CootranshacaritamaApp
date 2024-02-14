<?php

namespace App\Models\Radicacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class DocumentoEntrante extends Model
{
    use HasFactory;

    protected $table      = 'radicaciondocumentoentrante';
    protected $primaryKey = 'radoenid';
    protected $fillable   = ['peradoid','tipmedid','tierdeid','usuaid','depaid','muniid','radoenconsecutivo','radoenanio',
                            'radoenfechahoraradicado','radoenfechamaximarespuesta','radoenfechadocumento','radoenfechallegada',
                            'radoenpersonaentregadocumento','radoenasunto','radoentieneanexo','radoendescripcionanexo','radoentienecopia',
                            'radoenobservacion','radoenrequiererespuesta'];

    public static function obtenerConsecutivo($anioActual)
    {
        $consecutivoRadicado = DB::table('radicaciondocumentoentrante')->select('radoenconsecutivo as consecutivo')
                                ->where('radoenanio', $anioActual)->orderBy('radoenid', 'desc')->first();
        $consecutivo = ($consecutivoRadicado === null) ? 1 : $consecutivoRadicado->consecutivo + 1;
        return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
    }
}