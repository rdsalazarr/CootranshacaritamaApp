<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Http\Controllers\Controller;
use App\Util\convertirNumeroALetras;
use Illuminate\Http\Request;
use Exception, DB, URL;
use App\Util\generarPdf;
use App\Util\generales;

class ShowSolicitudCreditoController extends Controller
{
    public function index(Request $request)
    {
        $this->validate(request(),['codigo' => 'required|numeric']);

        try {
            $url                     = URL::to('/');
            $colocacion              = [];
            $cambiosEstadoColocacion = [];
            $colocacionLiquidacion   = [];
            $solicitudCredito        = DB::table('solicitudcredito as sc')->select('sc.solcrefechasolicitud','sc.solcredescripcion',
                                            'sc.solcrenumerocuota','sc.solcreobservacion','p.persdocumento', 'p.persprimernombre',
                                            'p.perssegundonombre','p.persprimerapellido','p.perssegundoapellido','p.persfechanacimiento',
                                            'p.persdireccion','p.perscorreoelectronico','p.persfechadexpedicion','p.persnumerotelefonofijo','p.persnumerocelular',
                                            'p.persgenero','p.persrutafoto','a.asocfechaingreso','lc.lincrenombre as lineaCredito','tesc.tiesscnombre as estadoActual',
                                            DB::raw("CONCAT(sc.solcretasa,' %') as tasaNominal"),
                                            DB::raw("CONCAT('$ ', FORMAT(sc.solcrevalorsolicitado, 0)) as valorSolicitado"),
                                            DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as nombreTipoIdentificacion"),
                                            DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafoto ) as fotografia"),
                                            DB::raw('(SELECT COUNT(coloid) AS coloid FROM colocacion WHERE solcreid = sc.solcreid) AS totalColocacion'))
                                            ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                                            ->join('persona as p', 'p.persid', '=', 'a.persid')
                                            ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                            ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                                            ->join('tipoestadosolicitudcredito as tesc', 'tesc.tiesscid', '=', 'sc.tiesscid')
                                            ->where('sc.solcreid', $request->codigo)->first();

            $cambiosEstadoSolicitudCredito =  DB::table('solicitudcreditocambioestado as cesc')
                                            ->select('cesc.socrcefechahora as fecha','cesc.socrceobservacion as observacion','tesc.tiesscnombre as estado',
                                                DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                                            ->join('tipoestadosolicitudcredito as tesc', 'tesc.tiesscid', '=', 'cesc.tiesscid')
                                            ->join('usuario as u', 'u.usuaid', '=', 'cesc.socrceusuaid')
                                            ->where('cesc.solcreid', $request->codigo)->get();

            if($solicitudCredito->totalColocacion > 0){

                $colocacion =   DB::table('colocacion as c')
                                    ->select('c.colofechahoraregistro','c.colovalordesembolsado','c.colotasa','c.colonumerocuota','tec.tiesclnombre',
                                    DB::raw("CONCAT('$ ', FORMAT(c.colovalordesembolsado, 0)) as valorDesembolsado"),
                                    DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"),
                                    DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                                    ->join('tipoestadocolocacion as tec', 'tec.tiesclid', '=', 'c.tiesclid')
                                    ->join('usuario as u', 'u.usuaid', '=', 'c.usuaid')
                                    ->where('c.solcreid', $request->codigo)->first();

                $colocacionLiquidacion  = DB::table('colocacionliquidacion as cl')
                                            ->select('cl.colliqnumerocuota as numeroCuota','cl.colliqfechavencimiento as fechaVencimiento',
                                            'cl.colliqnumerocomprobante as numeroComprobante','cl.colliqfechapago as fechaPago',
                                            DB::raw("CONCAT('$ ', FORMAT(cl.colliqvalorcuota, 0)) as valorCuota"),
                                            DB::raw("CONCAT('$ ', FORMAT(cl.colliqvalorpagado, 0)) as valorPagado"),
                                            DB::raw("CONCAT('$ ', FORMAT(cl.colliqsaldocapital, 0)) as saldoCapital"),
                                            DB::raw("CONCAT('$ ', FORMAT(cl.colliqvalorcapitalpagado, 0)) as capitalPagado"),
                                            DB::raw("CONCAT('$ ', FORMAT(cl.colliqvalorinterespagado, 0)) as interesPagado"),
                                            DB::raw("CONCAT('$ ', FORMAT(cl.colliqvalorinteresmora, 0)) as interesMora") 
                                            )
                                            ->join('colocacion as c', 'c.coloid', '=', 'cl.coloid')
                                            ->where('c.solcreid', $request->codigo)->get();

                $cambiosEstadoColocacion =  DB::table('colocacioncambioestado as cce')
                                        ->select('cce.cocaesfechahora as fecha','cce.cocaesobservacion as observacion','tec.tiesclnombre as estado',
                                            DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                                        ->join('tipoestadocolocacion as tec', 'tec.tiesclid', '=', 'cce.tiesclid')
                                        ->join('colocacion as c', 'c.coloid', '=', 'cce.coloid')
                                        ->join('usuario as u', 'u.usuaid', '=', 'cce.cocaesusuaid')
                                        ->where('c.solcreid', $request->codigo)->get();
            }

			return response()->json(['success' => true, 'solicitudCredito'     => $solicitudCredito,       'cambiosEstadoSolicitudCredito' => $cambiosEstadoSolicitudCredito,
                                                        'colocacion'            => $colocacion,             'cambiosEstadoColocacion'      => $cambiosEstadoColocacion,
                                                        'colocacionLiquidacion' => $colocacionLiquidacion]);
		} catch (Exception $error){		
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$error->getMessage()]);
		}
    }

    public function imprimir(Request $request)
    {
        $this->validate(request(),['solicitudId' => 'required|numeric',
                                    'url'         => 'required']);
        try {
            if($request->url === 'SOLICITUDCREDITO'){
                $dataDocumento = $this->generarSolicitudCredito($request);
            }else if($request->url === 'CARTAINSTRUCCIONES'){
                $dataDocumento = $this->generarCartaInstrucciones($request);
            }else if($request->url === 'FORMATO'){
                $dataDocumento = $this->generarformatoSolicitudCredito($request);
            }else{//De lo contrario genera el pagaré
                $dataDocumento = $this->generarPagare($request);
            }
			return response()->json(["data" => $dataDocumento]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    function generarSolicitudCredito($request){

        $colocacion = DB::table('colocacion as c')->select('c.coloid', 'c.colovalordesembolsado','c.colotasa', 'c.colonumerocuota','lc.lincrenombre',
                                'sc.solcredescripcion','c.colofechadesembolso', DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"),
                                DB::raw("CONCAT( p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                                ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                                ->join('persona as p', 'p.persid', '=', 'a.persid')
                                ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                                ->where('c.solcreid', $request->solicitudId)
                                ->where('sc.tiesscid', 'D')
                                ->first();

        $colocacionLiquidacion = DB::table('colocacionliquidacion')->select('colliqnumerocuota','colliqfechavencimiento', 'colliqvalorcuota')
                                    ->where('coloid', $colocacion->coloid)
                                    ->get();

        $arrayDatos = [ "fechaDesembolso"       => $colocacion->colofechadesembolso,
                        "lineaCredito"          => $colocacion->lincrenombre,
                        "nombreAsociado"        => $colocacion->nombreAsociado,
                        "descripcionCredito"    => $colocacion->solcredescripcion,
                        "valorSolicitado"       => $colocacion->colovalordesembolsado,
                        "tasaNominal"           => $colocacion->colotasa,
                        "plazoMensual"          => $colocacion->colonumerocuota,
                        "numeroColocacion"      => $colocacion->numeroColocacion,
                        "metodo"                => 'S'
                        ];

        $generarPdf = new generarPdf();
        return $generarPdf->solicitudCredito($arrayDatos, $colocacionLiquidacion);
    }

    function generarCartaInstrucciones($request){

        $colocacion = DB::table('colocacion as c')->select('c.colofechadesembolso','c.coloanio', 'c.colonumerodesembolso','p.persdocumento',DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"),
                                DB::raw("CONCAT( p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                                ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                                ->join('persona as p', 'p.persid', '=', 'a.persid')
                                ->where('c.solcreid', $request->solicitudId)
                                ->where('sc.tiesscid', 'D')
                                ->first();

        $dataInfoPdf         = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', 'cartaInstrucciones')->first();

        $generales           = new generales();
        $nombreAsociado      = $colocacion->nombreAsociado;
        $fechaLargaPrestamo  = $generales->formatearFecha($colocacion->colofechadesembolso);
        $numeroPagare        = $colocacion->numeroColocacion;
        $documento           = $colocacion->persdocumento;

        $documentoAsociado   = number_format($documento, 0, ',', '.');
        $buscar              = Array('nombreAsociado', 'numeroPagare', 'fechaLargaPrestamo');
        $remplazo            = Array( $nombreAsociado, $numeroPagare, $fechaLargaPrestamo); 
        $titulo              = str_replace($buscar,$remplazo,$dataInfoPdf->ingpdftitulo);
        $contenido           = str_replace($buscar,$remplazo,$dataInfoPdf->ingpdfcontenido);
        $generarPdf          = new generarPdf();
        return $generarPdf->cartaInstrucciones($titulo, $contenido, $numeroPagare, $documento, 'S');
    }

    function generarformatoSolicitudCredito($request){

        $generales  = new generales();
        $generarPdf = new generarPdf();
        $colocacion = DB::table('colocacion as c')->select('c.coloid','c.colovalordesembolsado', 'c.colonumerocuota','lc.lincrenombre',
                        DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"),'c.colonumerodesembolso','c.coloanio',
                        'c.colofechadesembolso', 'sc.solcredescripcion','sc.solcrefechasolicitud', 'ti.tipidesigla','p.persdocumento',
                        'v.vehiplaca','v.vehinumerointerno',DB::raw("CONCAT(tv.tipvehnombre,if(tv.tipvehreferencia is null ,'', tv.tipvehreferencia) ) as referenciaVehiculo"),
                        DB::raw("CONCAT( p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                        ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                        ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                        ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                        ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                        ->join('vehiculo as v', 'v.vehiid', '=', 'sc.vehiid')
                        ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                        ->where('c.solcreid', $request->solicitudId)
                        ->where('sc.tiesscid', 'D')
                        ->first();

        $colLiPrimerRegistro = DB::table('colocacionliquidacion') ->select('colliqvalorcuota')->where('coloid', $colocacion->coloid)->orderBy('colliqid')->first();

        $arrayDatos = [ "documentoAsociado" => $colocacion->persdocumento,
                        "nombreAsociado"    => $colocacion->nombreAsociado,
                        "vehiculo"          => $colocacion->referenciaVehiculo,
                        "numeroVehiculo"    => $colocacion->vehinumerointerno,
                        "placaVehiculo"     => $colocacion->vehiplaca,
                        "pagareNumero"      => $colocacion->numeroColocacion,
                        "tipoCredito"       => $colocacion->lincrenombre,
                        "montoCredito"      => $colocacion->colovalordesembolsado,
                        "valorCuota"        => $colLiPrimerRegistro->colliqvalorcuota,
                        "tiempoCredito"     => $colocacion->colonumerocuota,
                        "fechaDesembolso"   => mb_strtoupper($generales->formatearFechaLargaPagare($colocacion->colofechadesembolso),'UTF-8'),
                        "metodo"            => 'S'
                    ];

      return  $generarPdf->formatoSolicitudCredito($arrayDatos);
    }

    function generarPagare($request){

        $colocacion = DB::table('colocacion as c')->select('c.coloid', 'c.colovalordesembolsado','c.colotasa', 'c.colonumerocuota','lc.lincrenombre',
                                DB::raw("CONCAT(c.coloanio, c.colonumerodesembolso) as numeroColocacion"),'c.colonumerodesembolso','c.coloanio',
                                'c.colofechadesembolso', 'sc.solcredescripcion','sc.solcrefechasolicitud', 'ti.tipidesigla','p.persdocumento',
                                'v.vehiplaca','v.vehinumerointerno',DB::raw("CONCAT(tv.tipvehnombre,if(tv.tipvehreferencia is null ,'', tv.tipvehreferencia) ) as referenciaVehiculo"),
                            DB::raw("CONCAT( p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                            ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                            ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                            ->join('persona as p', 'p.persid', '=', 'a.persid')
                            ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                            ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                            ->join('vehiculo as v', 'v.vehiid', '=', 'sc.vehiid')
                            ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                            ->where('c.solcreid', $request->solicitudId)
                            ->where('sc.tiesscid', 'D')
                            ->first();

        $colLiPrimerRegistro    = DB::table('colocacionliquidacion') ->select('colliqfechavencimiento', 'colliqvalorcuota')->where('coloid', $colocacion->coloid)->orderBy('colliqid')->first();
        $colLiUltimoRegistro    = DB::table('colocacionliquidacion')->select('colliqfechavencimiento')->where('coloid', $colocacion->coloid) ->orderBy('colliqid', 'desc')->first();

        $generarPdf             = new generarPdf();
        $generales              = new generales();
        $convertirNumeroALetras = new convertirNumeroALetras();
        $dataRadicado           = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', 'pagareColocacion')->first();  
        $numeroPagare           = $colocacion->numeroColocacion;
        $valorTotalCredito      = $colocacion->colovalordesembolsado;
        $valorCredito           = number_format($valorTotalCredito, 0, ',', '.');
        $valorCuota             = number_format($colLiPrimerRegistro->colliqvalorcuota, 0, ',', '.');
        $fechaSolicitud         = $colocacion->solcrefechasolicitud;
        $fechaDesembolso        = $colocacion->colofechadesembolso;
        $fechaPrimeraCuota      = $colLiPrimerRegistro->colliqfechavencimiento;
        $fechaUltimaCuota       = $colLiUltimoRegistro->colliqfechavencimiento;
        $interesMensual         = $colocacion->colotasa;
        $numeroCuota            = $colocacion->colonumerocuota;
        $destinacionCredito     = $colocacion->solcredescripcion;
        $referenciaCredito      = $colocacion->coloanio;
        $garantiaCredito        = $colocacion->referenciaVehiculo;
        $numeroInternoVehiculo  = $colocacion->vehinumerointerno;
        $placaVehiculo          = $colocacion->vehiplaca;
        $nombreAsociado         = $colocacion->nombreAsociado;
        $tpDocumentoAsociado    = $colocacion->tipidesigla;
        $documento              = $colocacion->persdocumento;
        $documentoAsociado      = number_format($documento, 0, ',', '.');
        $interesMoratorio       = '0';
        $valorEnLetras          = trim($convertirNumeroALetras->valorEnLetras($valorTotalCredito));
        $fechaLargaPrestamo     = $generales->formatearFecha($colocacion->colofechadesembolso);  
        $fechaLargaDesembolso   = $generales->formatearFechaLargaPagare($colocacion->colofechadesembolso);

        $buscar                 = Array('numeroPagare', 'valorCredito', 'fechaSolicitud', 'fechaDesembolso','fechaPrimeraCuota','fechaUltimaCuota',
                                            'interesMensual','numeroCuota', 'destinacionCredito', 'referenciaCredito', 'garantiaCredito',
                                            'numeroInternoVehiculo', 'placaVehiculo', 'nombreAsociado', 'tpDocumentoAsociado', 'documentoAsociado', 'interesMoratorio',
                                            'valorEnLetras', 'fechaLargaDesembolso', 'valorCuota' ,'fechaLargaPrestamo'
                                        );
        $remplazo               = Array($numeroPagare, $valorCredito, $fechaSolicitud, $fechaDesembolso, $fechaPrimeraCuota, $fechaUltimaCuota,
                                            $interesMensual, $numeroCuota, $destinacionCredito, $referenciaCredito, $garantiaCredito,
                                            $numeroInternoVehiculo, $placaVehiculo, $nombreAsociado, $tpDocumentoAsociado, $documentoAsociado, $interesMoratorio,
                                            $valorEnLetras, $fechaLargaDesembolso, $valorCuota, $fechaLargaPrestamo
                                        );
        $titulo                 = str_replace($buscar,$remplazo,$dataRadicado->ingpdftitulo);
        $contenido              = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido);

        return $generarPdf->pagareColocacion($titulo, $contenido, $numeroPagare, $documento, 'S');
    }    
}