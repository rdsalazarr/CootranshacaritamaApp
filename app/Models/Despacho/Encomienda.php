<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\generarPdf;
use DB, Auth;

class Encomienda extends Model
{
    use HasFactory;

    protected $table      = 'encomienda';
    protected $primaryKey = 'encoid';
    protected $fillable   = ['agenid','usuaid','plarutid','perseridremitente','perseriddestino','depaidorigen','muniidorigen','depaiddestino','muniiddestino',
                            'tipencid','tiesenid','encoanio','encoconsecutivo','encofechahoraregistro','encocontenido','encocantidad', 'encovalordeclarado',
                            'encovalorenvio','encovalordomicilio','encovalorcomisionseguro','encovalorcomisionvehiculo', 'encovalorcomisionagencia','encovalorcomisionempresa',
                            'encoobservacion','encofecharecibido','encopagocontraentrega','encocontabilizada'];

    public static function generarFacturaPdf($encoid, $metodo = 'S'){
        $encomienda  = DB::table('encomienda as e')
                            ->select('e.encofechahoraregistro', DB::raw("CONCAT(e.encoanio,'',e.encoconsecutivo) as consecutivoEncomienda"),
                            'e.encovalortotal', 'e.encovalorcomisionseguro', DB::raw("if(e.encopagocontraentrega = 1 ,'SÍ', 'NO') as pagoContraEntrega"),
                            DB::raw("CONCAT(pr.agenid, '-', pr.plarutconsecutivo,' - ', mo.muninombre,' - ', md.muninombre) as nombreRuta"),
                            'te.tipencnombre','do.depanombre as deptoOrigen', 'mor.muninombre as municipioOrigen', 'dd.depanombre as deptoDestino', 'mde.muninombre as municipioDestino',
                            'e.encocontenido','e.encocantidad','e.encovalordeclarado','e.encovalorenvio','e.encovalordomicilio',
                            DB::raw("CONCAT(psr.perserprimernombre,' ',if(psr.persersegundonombre is null ,'', psr.persersegundonombre),' ',
                            psr.perserprimerapellido,' ',if(psr.persersegundoapellido is null ,' ', psr.persersegundoapellido)) as nombrePersonaRemitente"),
                            'psr.perserdireccion', 'psr.persernumerocelular',
                            DB::raw("CONCAT(psd.perserprimernombre,' ',if(psd.persersegundonombre is null ,'', psd.persersegundonombre),' ',
                            psd.perserprimerapellido,' ',if(psd.persersegundoapellido is null ,' ', psd.persersegundoapellido)) as nombrePersonaDestinatario"),
                            'psd.perserdireccion as direccionDestino',  'psd.persernumerocelular as numeroCelularDestino',
                            DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"), 'a.agennombre', 'a.agendireccion',
                            DB::raw("CONCAT(a.agentelefonocelular, if(a.agentelefonofijo is null ,'', ' - '), a.agentelefonofijo) as telefonoAgencia"),
                            DB::raw("(SELECT menimpvalor FROM mensajeimpresion WHERE menimpnombre = 'ENCOMIENDAS') AS mensajeEncomienda"))
                            ->join('personaservicio as psr', 'psr.perserid', '=', 'e.perseridremitente')
                            ->join('personaservicio as psd', 'psd.perserid', '=', 'e.perseriddestino')
                            ->join('tipoencomienda as te', 'te.tipencid', '=', 'e.tipencid')
                            ->join('planillaruta as pr', 'pr.plarutid', '=', 'e.plarutid')
                            ->join('ruta as r', 'r.rutaid', '=', 'pr.rutaid')
                            ->join('municipio as mo', function($join)
                            {
                                $join->on('mo.munidepaid', '=', 'r.depaidorigen');
                                $join->on('mo.muniid', '=', 'r.muniidorigen');
                            })
                            ->join('municipio as md', function($join)
                            {
                                $join->on('md.munidepaid', '=', 'r.depaiddestino');
                                $join->on('md.muniid', '=', 'r.muniiddestino');
                            })
                            ->join('departamento as do', 'do.depaid', '=', 'e.depaidorigen')
                            ->join('municipio as mor', function($join)
                            {
                                $join->on('mor.munidepaid', '=', 'e.depaidorigen');
                                $join->on('mor.muniid', '=', 'e.muniidorigen');
                            })
                            ->join('departamento as dd', 'dd.depaid', '=', 'e.depaiddestino')
                            ->join('municipio as mde', function($join)
                            {
                                $join->on('mde.munidepaid', '=', 'e.depaiddestino');
                                $join->on('mde.muniid', '=', 'e.muniiddestino');
                            })
                            ->join('usuario as u', 'u.usuaid', '=', 'e.usuaid')
                            ->join('agencia as a', 'a.agenid', '=', 'e.agenid')
                            ->where('e.encoid', $encoid)->first();

        $arrayDatos   = [
                            "fechaEncomienda"       => $encomienda->encofechahoraregistro,
                            "numeroEncomienda"      => $encomienda->consecutivoEncomienda,
                            "rutaEncomienda"        => $encomienda->nombreRuta,
                            "tipoEncomienda"        => $encomienda->tipencnombre,
                            "origenEncomienda"      => $encomienda->municipioOrigen,
                            "destinoEncomienda"     => $encomienda->municipioDestino,
                            "contenido"             => $encomienda->encocontenido,
                            "cantidad"              => $encomienda->encocantidad,
                            "pagoContraentrega"     => $encomienda->pagoContraEntrega,
                            "valorDeclarado"        => number_format($encomienda->encovalordeclarado, 0,',','.'),
                            "valorSeguro"           => number_format($encomienda->encovalorcomisionseguro, 0,',','.'),
                            "valorEnvio"            => number_format($encomienda->encovalorenvio, 0,',','.'),
                            "valorDomicilio"        => number_format($encomienda->encovalordomicilio, 0,',','.'),
                            "valorTotal"            => number_format($encomienda->encovalortotal, 0,',','.'),
                            "nombreRemitente"       => $encomienda->nombrePersonaRemitente,
                            "direccionRemitente"    => $encomienda->perserdireccion,
                            "telefonoRemitente"     => $encomienda->persernumerocelular,
                            "nombreDestinatario"    => $encomienda->nombrePersonaDestinatario,
                            "direccionDestinatario" => $encomienda->direccionDestino,
                            "telefonoDestinatario"  => $encomienda->numeroCelularDestino,
                            "usuarioElabora"        => $encomienda->nombreUsuario,
                            "nombreAgencia"         => $encomienda->agennombre,
                            "direccionAgencia"      => $encomienda->agendireccion,
                            "telefonoAgencia"       => $encomienda->telefonoAgencia,
                            "mensajePlanilla"       => $encomienda->mensajeEncomienda,
                            "metodo"                => $metodo
                        ];

        $generarPdf   = new generarPdf();
        return        $generarPdf->facturaEncomienda($arrayDatos);
    }
}