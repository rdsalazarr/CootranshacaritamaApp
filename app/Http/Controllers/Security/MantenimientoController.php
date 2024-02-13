<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\notificar;
use DB, PDF, Auth, URL, Artisan;
use Carbon\Carbon;
use setasign\Fpdi\Fpdi;
use App\Util\generarPdf;
use App\Util\generales;
use App\Util\convertirNumeroALetras;

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
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear'); 
        Artisan::call('config:cache');
        Artisan::call('view:cache'); 
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

        $rutaPdf            = public_path().'/archivos/radicacion/documentoEntrante/2023/270_1978917-cccoopigon.pdf';

        $informacioncorreo = DB::table('informacionnotificacioncorreo')->where('innocoid', 2)->first();
        
        $buscar          = Array('siglaCooperativa', 'nombreUsuario', 'usuarioSistema', 'nombreEmpresa','contrasenaSistema','urlSistema','nombreGerente');
        $remplazo        = Array($siglaCooperativa, $nombreUsuario,  $usuarioSistema, $nombreEmpresa, $contrasenaSistema, $urlSistema,$nombreGerente); 
        $asunto          = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
        $msg             = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido); 
        $enviarcopia     = $informacioncorreo->innocoenviarcopia;
        $enviarpiepagina = $informacioncorreo->innocoenviarpiepagina;
        $enviarcopia     = 0;
        $enviarpiepagina = 1;

        $mensajeCorreo = ', '.$notificar->correo([$email], $asunto, $msg, [$rutaPdf], $emailEmpresa, $enviarcopia, $enviarpiepagina);

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
        $tipoIdentificacion     = 'Cédula de ciudadania';
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
            "metodo"                 => 'I'
        ];


        $generarPdf->generarFormatoSolicitud($arrayDatos);


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