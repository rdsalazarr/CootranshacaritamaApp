<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\notificar;
use DB, PDF, Auth, URL, Artisan;
use Carbon\Carbon;

use App\Util\generarPdf;
use App\Util\generales;
use App\Util\GenerarContrato;

use App\Util\convertirNumeroALetras;


use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Caja\ComprobanteContable;
use App\Models\Caja\MovimientoCaja;



class MantenimientoController extends Controller
{
    /*public function __construct()
    {
        $this->middleware('auth')->except('clear');
    }

   /* public function __construct()
    {
      $this->middleware('guest',['only' => 'clear']);
    }*/

    public function clear()
    {
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:cache'); 
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('event:cache');
        
       // Artisan::call('optimize');
        return "Datos eliminados";
    } 

    public function down()
    {
        Artisan::call('down --secret="COOTRANSHACARITAMAAPP"');
        return response()->view('errors.503',['title' =>'Creando modo mantenimiento']);
    }

    public function up()
    {   
        Artisan::call('up');
        return response()->view('errors.upMantenimiento',['title' =>'Subiendo el modo mantenimiento']);
    }

    public function email(){
        $notificar       = new notificar();
        $fechaHoraActual = Carbon::now();

        $email             = 'rdsalazarr@ufpso.edu.co';
        $email             = 'radasa10@hotmail.com';
        $nombreUsuario     = 'RAMON DAVID SALAZAR RINCON';
        $siglaCooperativa  = 'COOTRANSHACARITAMA';
        $nombreEmpresa     = "Cooperativa de transporte HACARITAMA";  
        $usuarioSistema    = "RSALAZR";
        $contrasenaSistema = '123456789'; 
        $urlSistema        =  URL::to('/');
        $emailEmpresa      = '';
        $nombreGerente     = 'Luis manuel Ascanio'; 

        //$rutaPdf            = public_path().'/archivos/radicacion/documentoEntrante/2023/270_1978917-cccoopigon.pdf';

        //$informacioncorreo = DB::table('informacionnotificacioncorreo')->where('innocoid', 2)->first();
        $informacioncorreo = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarPagoMensualidadCompletada')->first();


        $buscar          = Array('siglaCooperativa', 'nombreUsuario', 'usuarioSistema', 'nombreEmpresa','contrasenaSistema','urlFirmaContrato','nombreGerente');
        $remplazo        = Array($siglaCooperativa, $nombreUsuario,  $usuarioSistema, $nombreEmpresa, $contrasenaSistema, $urlSistema,$nombreGerente); 
        $asunto          = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
        $msg             = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido); 
        $enviarcopia     = $informacioncorreo->innocoenviarcopia;
        $enviarpiepagina = $informacioncorreo->innocoenviarpiepagina;
        $enviarcopia     = 0;
        $enviarpiepagina = 1;

        $mensajeCorreo = ', '.$notificar->correo([$email], $asunto, $msg, [], $emailEmpresa, $enviarcopia, $enviarpiepagina);

       dd($mensajeCorreo);      
    }

    function calcularPagoCuotaMensualArray($montoPrestamo, $tasaInteresMensual, $plazo, $fechaVencimiento, $interesMora) {
        //dd($montoPrestamo, $tasaInteresMensual, $plazo, $fechaVencimiento, $interesMora);

        $generales  = new generales();  

		// Calcular la fecha actual
		$fechaActual = Carbon::now();
        $fechaVencimiento = Carbon::parse($fechaVencimiento);

        		// Calcular la cuota mensual
		if ($tasaInteresMensual == 0.0) {
			$valorCuota = $montoPrestamo / $plazo;
		} else {
			$tasaInteresMensual = $tasaInteresMensual / 100; // Convertir la tasa a formato decimal
			$denominador = 1 - pow(1 + $tasaInteresMensual, -$plazo);    
			$valorCuota = ($montoPrestamo * $tasaInteresMensual) / $denominador;
		}

	
		// Inicializar los valores de retorno
		$resultado = [
			'cuota' => null,
			'mora' => 0,
			'descuento' => 0
		];
	
		// Verificar si la fecha de vencimiento es anterior a la fecha actual
		if ($fechaVencimiento->lt($fechaActual)) {
			// Calcular los días de mora
			$diasMora                 = $fechaActual->diffInDays($fechaVencimiento);
            $porcentajeInteresMensual = $interesMora / 100;
            $tasaInteresDiaria        = $tasaInteresMensual / 30;

            //dd($valorCuota, $diasMora, $porcentajeInteresMensual, $tasaInteresDiaria);

            $interesMoraTotal = $diasMora  * $porcentajeInteresMensual * $tasaInteresDiaria;

            dd($interesMoraTotal);
			
			// Calcular el interés de mora
			$interesMensual   = $interesMora / 100;    
			$interesDiario    = $interesMensual / 30;
			$interesMoraTotal = $interesDiario  * $diasMora;

            dd($interesMoraTotal);
	
			// Actualizar el valor de la mora en el resultado
			$resultado['mora'] = $interesMoraTotal;

            $valorCuota += $interesMoraTotal;
		} else {
			// Calcular los días anticipados (días antes del vencimiento)
			$diasAnticipados = $fechaVencimiento->diffInDays($fechaActual);
			
			// Calcular el descuento por pago anticipado
			$descuentoDiario = $tasaInteresMensual / 100; // Utilizamos la tasa de interés mensual como descuento diario
			$descuentoTotal = $descuentoDiario * $montoPrestamo * $diasAnticipados;
	
			// Actualizar el valor del descuento en el resultado
			$resultado['descuento'] = $descuentoTotal;

            $valorCuota -= $descuentoTotal;
		}	

	
		// Actualizar el valor de la cuota en el resultado
		$resultado['cuota'] = $generales->redondearMilSiguiente($valorCuota);
	
		return $resultado;
	}

    function calcularValorInteresDiario($montoPrestamo, $tasaInteresMensual, $fechaVencimiento, $interesMora, $numeroDiasCambioFecha){
        $generales  = new generales();      

        $fechaActual         = Carbon::now();
        $fechaVencimiento    = Carbon::parse($fechaVencimiento);
        $interesMensual      = $montoPrestamo * ($tasaInteresMensual / 100);
        $valorCambioFechas   = 0; 
        $totalInteresMora    = 0;
        $totalValorDescuento = 0;

        dd($numeroDiasCambioFecha);

        if($numeroDiasCambioFecha > 0){
            $valorCambioFechas = $montoPrestamo * ($tasaInteresMensual / 100) * ($numeroDiasCambioFecha / 365);
            $interesMensual   += $generales->redonderarCienMasCercano($valorCambioFechas);
        }

        if ($fechaVencimiento->lt($fechaActual)) {//Tiene mora
            $diasMora         = $fechaActual->diffInDays($fechaVencimiento);
            $interesMora      = $montoPrestamo * ($interesMora / 100) * ($diasMora / 365);
            $totalInteresMora = $generales->redonderarCienMasCercano($interesMora); 
        }else{
            $diasAnticipado      = $fechaActual->diffInDays($fechaVencimiento) + 1; //No toma la fecha actual
            $valorDescuento      = $montoPrestamo * ($tasaInteresMensual / 100) * ($diasAnticipado / 365);
            $totalValorDescuento = $generales->redonderarCienMasCercano($valorDescuento);
        }

        $resultado = [
			'valorIntereses'   => $interesMensual,
			'valorInteresMora' => $totalInteresMora,
			'valorDescuento'   => $totalValorDescuento
		];

        return $resultado;
    }

    function calcularDiasCambiosFechaDesembolso($fechaDesembolso, $fechaVencimiento){
        $fechaDesembolso  = Carbon::parse($fechaDesembolso);
        $fechaVencimiento = Carbon::parse($fechaVencimiento);
        return  $fechaDesembolso->diffInDays($fechaVencimiento);
    }

    function obtenerFechaPagoCuota($fecha) {
		$fechaActual   = Carbon::parse($fecha);
        $nuevaFecha    = Carbon::parse($fecha);
        $diaFecha      = $fechaActual->day;
        $mesFecha      = $fechaActual->month;
		$diasAdicionar = 30;
		$nuevaFecha    = $fechaActual->addDays($diasAdicionar);

        // Verificar si la fecha resultante es 28 o 29 de febrero para ajustar al 01 de abril
		if (($diaFecha === 28 || $diaFecha === 29) && $mesFecha === 2) {            
            $nuevaFecha->setMonth(4);
            $nuevaFecha->setDay(1);
        }

        return $nuevaFecha;
    }

    function obtenerFechaPagoCuota12($fecha) {
        $fechaActual   = Carbon::parse($fecha);
        $nuevaFecha    = Carbon::parse($fecha);
        $diaFecha      = $fechaActual->day;
        $mesFecha      = $fechaActual->month;
        $anioFecha     = $fechaActual->year;
        $diasAdicionar = 30;

       // dd($mesFecha);
    
        // Si es 29, 30 o 31, ajustar al primer día del siguiente mes
        if ($diaFecha >= 29 && $diaFecha <= 31) {
            // Si es diciembre, ajustar a enero del siguiente año
            if ($mesFecha == 12) {
                dd("hola");
                $nuevaFecha->year($anioFecha + 1)->month(1)->day(1);
            } else {
                //dd("hola 2");
                $nuevaFecha->addMonthsNoOverflow(1)->startOfMonth();
            }
        } else {
            dd('hola');
            $nuevaFecha->addDays($diasAdicionar);
        }
    
        // Verificar si es febrero y ajustar al 1 de abril
        if ($mesFecha == 2 && $diaFecha >= 28) {
            $nuevaFecha->year($anioFecha)->month(4)->day(1);
        }
    
        return $nuevaFecha;
    }

    function obtenerFechaPagoCuota1aa($fecha) {
        $fechaActual   = Carbon::parse($fecha);
        $nuevaFecha    = Carbon::parse($fecha);
        $diaFecha      = $fechaActual->day;
        $mesFecha      = $fechaActual->month;
        $anioFecha     = $fechaActual->year;
        $diasAdicionar = 30;
    
        // Si es 29, 30 o 31, ajustar al primer día del siguiente mes
        if ($diaFecha >= 29 && $diaFecha <= 31) {
            // Si es diciembre, ajustar a enero del siguiente año
            if ($mesFecha == 12) {
                $nuevaFecha->year($anioFecha + 1)->month(1)->day(1);
            } else {
                $nuevaFecha->addMonthsNoOverflow(1)->startOfMonth();
            }
        } else {
            $nuevaFecha->addDays($diasAdicionar);
        }
    
        // Verificar si es febrero y ajustar al 1 de abril
        if ($mesFecha == 2 && $diaFecha >= 28) {
            $nuevaFecha->year($anioFecha)->month(4)->day(1);
        }
    
        // Añadir los 30 días adicionales
        $nuevaFecha->addDays($diasAdicionar);
    
        return $nuevaFecha;
    }


    function obtenerFechaPagoCuota1($fecha) {
        $fechaActual   = Carbon::parse($fecha);
        $nuevaFecha    = Carbon::parse($fecha);
        $diaFecha      = $fechaActual->day;
        $mesFecha      = $fechaActual->month;
        $anioFecha     = $fechaActual->year;
        $diasAdicionar = 30;
    
        // Si es 29, 30 o 31, ajustar al primer día del siguiente mes
        if ($diaFecha >= 29 && $diaFecha <= 31) {
            // Si es diciembre, ajustar a enero del siguiente año
            if ($mesFecha == 12) {
                $nuevaFecha->year($anioFecha + 1)->month(1)->day(1);
            } else {
                $nuevaFecha->addMonthsNoOverflow(1)->startOfMonth();
            }
        } else {
            $nuevaFecha->addDays($diasAdicionar);
        }
    
        // Verificar si es febrero y ajustar al 1 de abril
        if ($mesFecha == 2 && $diaFecha >= 28) {
            $nuevaFecha->year($anioFecha)->month(4)->day(1);
        }
    
        // Añadir los 30 días adicionales
        $nuevaFecha->addDays($diasAdicionar);
    
        return $nuevaFecha;
    }

    function fechaMesSiguiente($fecha) {
        // Parseamos la fecha utilizando Carbon
        $fechaCarbon = Carbon::parse($fecha);
        
        // Sumamos un mes a la fecha actual
        $fechaSiguiente = $fechaCarbon->addMonth();
    
        return $fechaSiguiente;
    }


    function obtenerFechaPagoCuotaMia($fecha) {
		$fechaActual  = Carbon::parse($fecha);
        $nuevaFecha   = Carbon::parse($fecha);
        $diaFecha     = $fechaActual->day;
        $mesFecha     = $fechaActual->month;
  
        // Verificar si la fecha resultante es 28 o 29 de febrero para ajustar al 01 de abril
		/*if (($diaFecha === 28 || $diaFecha === 29) && $mesFecha === 2) {            
            $nuevaFecha->setMonth(4);
            $nuevaFecha->setDay(1);
        }else if ($diaFecha >= 29 && $diaFecha <= 31) {
            $nuevaFecha->setMonth($mesFecha + 1);
            $nuevaFecha->setDay(1); 
        }else{           
            $nuevaFecha = $nuevaFecha->addMonth();
        }*/
        if ($diaFecha >= 29 && $diaFecha <= 31) {
            $nuevaFecha->setMonth($mesFecha + 1);
            $nuevaFecha->setDay(1); 
        }else{
            $nuevaFecha = $fechaActual->addMonth();
        }

        return $nuevaFecha;
    }

    function obtenerFechaInicialColocacion(){
        $fechaHoraActual  = Carbon::now();
        $fechaActual      = Carbon::parse($fechaHoraActual->format('Y-m-d'));
        $diaFecha         = $fechaActual->day;
        $mesFecha         = $fechaActual->month;
        $nuevaFecha       = Carbon::parse($fechaActual);

        if ($diaFecha >= 29 && $diaFecha <= 31) {
            $nuevaFecha->setMonth($mesFecha + 1);
            $nuevaFecha->setDay(1);
        }

        return $nuevaFecha;
    }
    
    public function Pdf()
    {
        $notificar       = new notificar();

        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        
        $searcComprobanteContables = DB::table('comprobantecontable')->select('comconid', 'usuaid','agenid','cajaid')->whereDate('comconestado', 'A')->get();
        foreach($searcComprobanteContables as $searcComprobanteContable){
            $comconid  = $searcComprobanteContable->comconid;
            $idUsuario = $searcComprobanteContable->usuaid;
            $agenciaId = $searcComprobanteContable->agenid;
            $cajaId    = $searcComprobanteContable->cajaid;

            //movimientocaja as mc
            $comprobanteContableId = DB::table('comprobantecontable as cc')
                                        ->select('cc.comconid', 'cc.movcajid', 'cc.comcondescripcion', 'a.agennombre', 'c.cajanumero','u.usuaalias',
                                        DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"), 
                                        DB::raw('DATE(cc.comconfechahora) as fechaComprobante'), 
                                        DB::raw("(SELECT movcajsaldoinicial from movimientocaja 
                                                        where date(movcajfechahoraapertura) = '$fechaActual' 
                                                        and usuaid = '$idUsuario' 
                                                        and cajaid = '$cajaId') as saldoInicial"),
                                        DB::raw("CONCAT(cc.comconanio, cc.comconconsecutivo) as numeroComprobante"),
                                        DB::raw("(SELECT SUM(ccd.cocodemonto)
                                                FROM comprobantecontabledetalle as ccd
                                                INNER JOIN cuentacontable as cc ON cc.cueconid = ccd.cueconid
                                                INNER JOIN comprobantecontable as cct ON cct.comconid = ccd.comconid
                                                INNER JOIN movimientocaja as mc ON mc.movcajid = cct.movcajid
                                                WHERE cc.cueconnaturaleza = 'D'
                                                AND mc.usuaid = '$idUsuario'
                                                AND mc.cajaid = '$cajaId'
                                                AND cct.agenid = '$agenciaId'
                                                AND DATE(mc.movcajfechahoraapertura) = '$fechaActual'
                                            ) AS valorDebito"))
                                        ->join('agencia as a', 'a.agenid', '=', 'cc.agenid')
                                        ->join('caja as c', 'c.cajaid', '=', 'cc.cajaid')
                                        ->join('usuario as u', 'u.usuaid', '=', 'cc.usuaid')
                                        ->whereDate('cc.comconfechahora', $fechaActual)
                                        ->where('cc.usuaid', $idUsuario)
                                        ->where('cc.agenid', $agenciaId)
                                        ->where('cc.cajaid', $cajaId)
                                        ->first(); 

            $nombreUsuario         = $comprobanteContableId->nombreUsuario;
            $correoUsuario         = $comprobanteContableId->usuaalias;
            $nuemeroComprobante    = $comprobanteContableId->numeroComprobante;
            $fechaComprobante      = $comprobanteContableId->fechaComprobante;
            $nombreAgencia         = $comprobanteContableId->agennombre;
            $numeroCaja            = $comprobanteContableId->cajanumero;
            $conceptoComprobante   = $comprobanteContableId->comcondescripcion;
            $saldoCajaCerrar       = $comprobanteContableId->saldoInicial + $comprobanteContableId->valorDebito;
            $movimientoCajaId      = $comprobanteContableId->movcajid;
            $comprobanteContableId = $comprobanteContableId->comconid;

            $comprobantecontable                        = ComprobanteContable::findOrFail($comprobanteContableId);
            $comprobantecontable->comconfechahoracierre = $fechaHoraActual;
            $comprobantecontable->comconestado          = 'C';
            //$comprobantecontable->save();

            $comprobanteContableDetalles = DB::table('comprobantecontabledetalle')->select('cocodeid')->whereDate('comconid', $comconid)->get();
            foreach($comprobanteContableDetalles as $comprobanteContableDetalleId){
                $comprobantecontabledetalle                       = ComprobanteContableDetalle::findOrFail($comprobanteContableDetalleId->cocodeid);
                $comprobantecontabledetalle->cocodecontabilizado = true;
               // $comprobantecontabledetalle->save();
            }

            $movimientocaja                        = MovimientoCaja::findOrFail($movimientoCajaId);
            $movimientocaja->movcajfechahoracierre = $fechaHoraActual;
            $movimientocaja->movcajsaldofinal      = $saldoCajaCerrar;
           // $movimientocaja->save();

            $arrayDatos = [ 
                    "nombreUsuario"       => $nombreUsuario,
                    "nuemeroComprobante"  => $nuemeroComprobante,
                    "fechaComprobante"    => $fechaComprobante,
                    "nombreAgencia"       => $nombreAgencia,
                    "numeroCaja"          => $numeroCaja,
                    "conceptoComprobante" => $conceptoComprobante,
                    "mensajeImpresion"    => 'Documento impreso el dia '.$fechaHoraActual,
                    "metodo"              => 'F'
                ];

            $generarPdf = new generarPdf();
            $rutaPdf    = []; 
            $dataFactura = $generarPdf->generarComprobanteContable($arrayDatos, MovimientoCaja::obtenerMovimientosContablesPdf($fechaActual, $idUsuario, $agenciaId, $cajaId));
            array_push($rutaPdf, $dataFactura);

            $nombreGerente = 'Pedro quintero';
            $correoEmpresa = 'rdsalazarr@ufpso.edu.co';

            $informacionCorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificacionCierreCajaAutomatico')->first();
            $buscar             = Array('nombreEmpleado', 'nombreGerente');
            $remplazo           = Array($nombreUsuario, $nombreGerente); 
            $innocoasunto       = $informacionCorreo->innocoasunto;
            $innococontenido    = $informacionCorreo->innococontenido;
            $enviarcopia        = $informacionCorreo->innocoenviarcopia;
            $enviarpiepagina    = $informacionCorreo->innocoenviarpiepagina;
            $asunto             = str_replace($buscar, $remplazo, $innocoasunto);
            $msg                = str_replace($buscar, $remplazo, $innococontenido);
            $mensajeNotificar   = $notificar->correo([$correoUsuario], $asunto, $msg, [$rutaPdf], $correoEmpresa, $enviarcopia, $enviarpiepagina);

        }

        



       // GenerarContrato::vehiculo(6, 'I');

        /*$fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');

        dd($fechaActual);*/
       /* $generales  = new generales();  

        $fechasSiguientes = $generales->obtenerPrimerValorMensualidad('2024-01-15', 105000);

        //$fechasSiguientes = $generales->obtenerFechasCompromisoVehiculo('2024-02-04');

        dd($fechasSiguientes);*/

        
        /*$generarPdf = new generarPdf();
        $rutaCarpeta             = public_path().'/archivos/radicacion/documentoEntrante/2024/';
        $nombreArchivo = 'prueba_01.pdf';
        $rutaPdf        = $rutaCarpeta.'/'.$nombreArchivo;
                $dataCopias     = [];
                $dataRadicado   = DB::table('radicaciondocumentoentrante as rde')
                                    ->select('rde.radoenfechahoraradicado  as fechaRadicado', DB::raw("CONCAT(rde.radoenanio,'-', rde.radoenconsecutivo) as consecutivo"),
                                            'd.depenombre as dependencia','u.usuaalias as usuario', 'prd.peradocorreo  as correo', 'rde.radoenasunto as asunto')
                                    ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                                    ->join('radicaciondocentdependencia as rded', function($join)
                                        {
                                            $join->on('rded.radoenid', '=', 'rde.radoenid');
                                            $join->where('rded.radoedescopia', false);
                                        })
                                    ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                                    ->join('usuario as u', 'u.usuaid', '=', 'rde.usuaid')
                                    ->where('rde.radoenid', 4)->first();

                                    $dataCopias =  DB::table('radicaciondocentdependencia as rded')
                                    ->select('d.depenombre as dependencia','d.depecorreo')
                                    ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                                    ->where('rded.radoenid', 4)
                                    ->where('rded.radoedescopia', true)->get();

                $generarPdf->radicarDocumentoExterno($rutaCarpeta, $nombreArchivo, $dataRadicado, $dataCopias, false);


        /*$tipoIdentificacion     = 'Cédula de ciudadania';
        $documentoIdentidad     = '1.978.917';
        $nombresSolicitante     = 'RAMÓN DAVID';
        $apellidosSolicitante   = 'SALAZAR RINCÓN';
        $nombreCompleto         = $nombresSolicitante.' '.$apellidosSolicitante;
        $direccionSolicitante   = 'Calle 4 numero 39 48 sur del oeste con avenida';
        $telefonoSolicitante    = '3204018506';
        $correoSolicitante      = 'radasa10@hotmail.com';
        $tipoSolicitud          = 'Queja';
        $tipoMedio              = 'Impreso';
        $fechaRegistro          = '2024-02-13';
        $fechaIncidente         = '2024-02-13 14:00';
        $conductorInvolucrado   = 'Pedro coronel';
        $vehiculoInvolucrado    = 'Vehiculo de prueba';
        $motivoSolicitud        = 'Motivo de la solicitud';
        $observacionesSolicitud = 'Observaciones a la solicitud';

        $generarPdf = new generarPdf();

        $arrayDatos = [ 
            "tipoIdentificacion"     => $tipoIdentificacion,
            "documentoIdentidad"     => $documentoIdentidad,
            "nombresSolicitante"     => $nombresSolicitante,
            "apellidosSolicitante"   => $apellidosSolicitante,
            "direccionSolicitante"   => $direccionSolicitante,
            "telefonoSolicitante"    => $telefonoSolicitante,
            "correoSolicitante"      => $correoSolicitante,
            "tipoSolicitud"          => $tipoSolicitud,
            "tipoMedio"              => $tipoMedio,
            "fechaRegistro"          => $fechaRegistro,
            "fechaIncidente"         => $fechaIncidente,
            "conductorInvolucrado"   => $conductorInvolucrado,
            "vehiculoInvolucrado"    => $vehiculoInvolucrado,
            "motivoSolicitud"        => $motivoSolicitud,
            "observacionesSolicitud" => $observacionesSolicitud,
            "anioRadicado"           => '2024',
            "nombreArchivo"          => 'prueba.pdf',
            "metodo"                 => 'I'
        ];


        $generarPdf->generarFormatoSolicitud($arrayDatos);*/


        /*$generales  = new generales();  
        $generarPdf = new generarPdf();
        $empresa            = $generarPdf->consultarEmpresa();
		$direccionEmpresa 	= $empresa->emprdireccion;	
		$barrioEmpresa    	= $empresa->emprbarrio;
		$telefonoEmpresa  	= $empresa->emprtelefonofijo;
		$celularEmpresa   	= $empresa->emprtelefonocelular;
		$urlEmpresa       	= $empresa->emprurl;
		$nombreEmpresa      = $empresa->emprnombre;
		$siglaEmpresa       = $empresa->emprsigla;
		$nit                = $empresa->nit;
		$personeriaJuridica = $empresa->emprpersoneriajuridica;
		$logoEmpresa        = $empresa->emprlogo;

        $titulo             = 'Formato de recepción de peticiones, quejas, reclarmos, sugerencias y felicitaciones';
        $tituloFormato      = 'PETICIÓN, QUEJA, RECLAMO, SUGERENCIA Y/O FELICITACIÓN';
        $versionFormato     = '02'; 
        $numeroFormato      = 'F-GC-10'; 
        $fechaFormato       = '08/11/2016'; 
        $areaFormato        = 'GESTIÓN CALIDAD';

        PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Solicitud, Queja, Reclamo, Sugerencia, Felicitación, Formato, '.$siglaEmpresa);
        PDF::SetTitle($titulo);

        $generarPdf->headerFormato($tituloFormato, $versionFormato, $numeroFormato, $fechaFormato, $areaFormato, $siglaEmpresa, $logoEmpresa);
		$generarPdf->footerDocumental($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa);

        PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
        PDF::SetMargins(12, 30 , 12);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(32); 

        $mensajeFormato = 'Con el fin de mejorar cada dia en la prestacion de nuestros servicios y/o Producto suministrado, ';
        $mensajeFormato .= 'solicitamos diligenciar este formato con la finalidad de conocer, tramitar y dar solucion a las ';
        $mensajeFormato .= 'peticiones, quejas, reclamos, sugerencias y/o felicitaciones presentadas por usted(s).';

        PDF::SetTextColor(179,179,180);
        PDF::SetFont('helvetica','I',9);
        PDF::MultiCell(0,3,$mensajeFormato."\n",0,'J',0);     
        PDF::SetTextColor(0);

        PDF::Ln(4);
        PDF::SetFillColor(232, 231, 231);
        PDF::SetFont('helvetica','B',11);
        PDF::Cell(188,4,'INFORMACIÓN DEL USUARIO',1,0,'',true);
        PDF::Ln(8);     
        
        PDF::Cell(44,4,'Tipo de identificación:',0,0,'');
        PDF::SetFont('helvetica','',11);
        PDF::Cell(60,4,$tipoIdentificacion,0,0,'');
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(26,4,'Documento: ',0,0,'');
        PDF::SetFont('helvetica','',11);
        PDF::Cell(58,4,$documentoIdentidad,0,0,'');
        PDF::Ln(4);
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Nombres (s): ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(60,4,substr($nombresSolicitante, 0, 23),0,0,'');
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(26,4,'Apellido (s): ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(58,4,substr($apellidosSolicitante, 0, 23),0,0,'');
        PDF::Ln(4);
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Correo electrónico: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(60,4,substr($correoSolicitante, 0, 27),0,0,'');
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(26,4,'Teléfono: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(58,4,$telefonoSolicitante,0,0,'');
        PDF::Ln(4);
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Dirección: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::MultiCell(144, 4, $direccionSolicitante, 0, 'L', false, 1);
        PDF::Ln(4);
        PDF::SetFillColor(232, 231, 231);
        PDF::SetFont('helvetica','B',11);
        PDF::Cell(188,4,'INFORMACIÓN DE LA SOLICITUD',1,0,'',true);
        PDF::Ln(8);

        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Tipo de solicitud: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(60,4,$tipoSolicitud,0,0,'');
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(42,4,'Tipo de medio: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(40,4,$tipoMedio,0,0,'');
        PDF::Ln(5);

        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Fecha de registro: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(60,4,$fechaRegistro,0,0,'');
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(42,4,'Fecha de incidente:',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::Cell(40,4,$fechaIncidente,0,0,'');
        PDF::Ln(5);

        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Conductor: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::MultiCell(144, 4, $conductorInvolucrado, 0, 'L', false, 1);
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Vehículo: ',0,0,'');
        PDF::SetFont('helvetica','',12);
        PDF::MultiCell(144, 4, $vehiculoInvolucrado, 0, 'L', false, 1);
        PDF::Ln(8);
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Motivo: ',0,0,'');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',12);
        PDF::MultiCell(188,4,$motivoSolicitud."\n",0,'J');

        PDF::Ln(8);
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(44,4,'Observaciones: ',0,0,'');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',12);
        PDF::MultiCell(188,4,$observacionesSolicitud."\n",0,'J');

        PDF::Ln(12);
        PDF::SetFont('helvetica','I',12);
        PDF::MultiCell(188,4,$nombreCompleto."\n",0,'J');   
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(90,4,'FIRMA DE QUIEN PRESENTA LA QUEJA ','T',0,'L');


        PDF::Output();*/
       
	}
}