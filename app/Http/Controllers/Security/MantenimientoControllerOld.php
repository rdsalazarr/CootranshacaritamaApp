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

class MantenimientoControllerOld extends Controller
{
        
    public function Pdf1()
    {  
		/*$dataRadicado = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfid', 1)->first();       
        $numeroContrato             = 'A02765';
        $fechaContrato              = '2023-10-19';
        $nombreGerente              = 'LUIS MANUEL ASCANIO CLARO';
        $tpDocumentoGerente         = 'CC';
        $documentoGerente           = number_format('5036123', 0, ',', '.');
        $ciudadExpDocumentoGerente  = 'OCAÑA';
        $nombreAsociado             = 'DIONISIO DE JESUS ANGARITA ANGARITA ';
        $tpDocumentoAsociado        = 'CC';
        $documentoAsociado          = number_format('88143913', 0, ',', '.');
        $ciudadExpDocumentoAsociado = 'OCAÑA';
        $direccionAsociado          = 'CALLE 18 N 22-85 LAS COLINAS';
        $telefonoAsociado           = '3152190167';
        $placaVehiculo              = 'TFT187';
        $numeroInternoVehiculo      = '208';
        $claseVehiculo              = 'AUTOMÓVIL';
        $cilindrajeVehiculo         = '1598';
        $carroceriaVehiculo         = 'Sedán';
        $modeloVehiculo             = '2020';
        $marcaVehiculo              = 'RENAULT';
        $colorVehiculo              = 'BLANCO GLACIAL (V)';
        $capacidadVehiculo          = '04';  
        $documentosAdionales        = '1 Fotografía, Fotocopia Cédula, Fotocopia Tarjeta de Propiedad a su nombre y Reseña del DAS.';
        $observacionGeneral         = 'VEHICULO EL DE PLACAS TFT187 INGRESA EN REPOSICION DEl UUA585';

        $buscar                     = Array('numeroContrato', 'fechaContrato', 'nombreGerente', 'tpDocumentoGerente','documentoGerente','ciudadExpDocumentoGerente',
                                            'nombreAsociado','tpDocumentoAsociado', 'documentoAsociado', 'ciudadExpDocumentoAsociado', 'direccionAsociado',
                                            'telefonoAsociado', 'placaVehiculo', 'numeroInternoVehiculo', 'claseVehiculo', 'cilindrajeVehiculo', 'carroceriaVehiculo',
                                            'modeloVehiculo', 'marcaVehiculo', 'colorVehiculo', 'capacidadVehiculo', 'documentosAdionales', 'observacionGeneral'
                                        );
        $remplazo                   = Array($numeroContrato, $fechaContrato, $nombreGerente, $tpDocumentoGerente, $documentoGerente, $ciudadExpDocumentoGerente,
                                            $nombreAsociado, $tpDocumentoAsociado, $documentoAsociado, $ciudadExpDocumentoAsociado, $direccionAsociado,
                                            $telefonoAsociado, $placaVehiculo, $numeroInternoVehiculo, $claseVehiculo, $cilindrajeVehiculo, $carroceriaVehiculo,
                                            $modeloVehiculo, $marcaVehiculo, $colorVehiculo, $capacidadVehiculo, $documentosAdionales, $observacionGeneral
                                        ); 
        $titulo                      = str_replace($buscar,$remplazo,$dataRadicado->ingpdftitulo);
        $contenido                   = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido); 
        $generarPdf                  = new generarPdf();
        $generarPdf->generarContratoVehiculo($titulo, $contenido, $numeroContrato, $placaVehiculo, 'I' );*/

        /*$convertirNumeroALetras = new convertirNumeroALetras();        
        $dataRadicado = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfid', 2)->first();  
        $numeroPagare               = '55546';
        $valorTotalCredito          = '600000';
        $valorCredito               = number_format($valorTotalCredito, 0, ',', '.');
        $valorCuota                 = number_format('100000', 0, ',', '.');        
        $fechaSolicitud             = '2023-09-04'; 
        $fechaDesembolso            = '2023-09-04';
        $fechaPrimeraCuota          = '2023-10-03';
        $fechaUltimaCuota           = '2024-03-03';
        $interesMensual             = '1.09';
        $numeroCuota                = '6';
        $destinacionCredito         = 'VARIOS';
        $referenciaCredito          = '2023';
        $garantiaCredito            = 'VEHICULO';
        $numeroInternoVehiculo      = '471';
        $placaVehiculo              = 'TFT187';
        $nombreAsociado             = 'DIONISIO DE JESUS ANGARITA ANGARITA ';
        $tpDocumentoAsociado        = 'CC';
        $documento                  = '88143913';
        $documentoAsociado          = number_format($documento, 0, ',', '.');
        $interesMoratorio           = '1.02';
        $valorEnLetras              = trim($convertirNumeroALetras->valorEnLetras($valorTotalCredito));
        $fechaLargaPrestamo         = '4 de septiembre de 2023' ;
        $fechaLargaDesembolso       = '05 días del mes de septiembre de 2023';

        $buscar                     = Array('numeroPagare', 'valorCredito', 'fechaSolicitud', 'fechaDesembolso','fechaPrimeraCuota','fechaUltimaCuota',
                                            'interesMensual','numeroCuota', 'destinacionCredito', 'referenciaCredito', 'garantiaCredito',
                                            'numeroInternoVehiculo', 'placaVehiculo', 'nombreAsociado', 'tpDocumentoAsociado', 'documentoAsociado', 'interesMoratorio',
                                            'valorEnLetras', 'fechaLargaDesembolso', 'valorCuota'
                                        );
        $remplazo                   = Array($numeroPagare, $valorCredito, $fechaSolicitud, $fechaDesembolso, $fechaPrimeraCuota, $fechaUltimaCuota,
                                            $interesMensual, $numeroCuota, $destinacionCredito, $referenciaCredito, $garantiaCredito,
                                            $numeroInternoVehiculo, $placaVehiculo, $nombreAsociado, $tpDocumentoAsociado, $documentoAsociado, $interesMoratorio,
                                            $valorEnLetras, $fechaLargaDesembolso, $valorCuota
                                        ); 
        $titulo                      = str_replace($buscar,$remplazo,$dataRadicado->ingpdftitulo);
        $contenido                   = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido);

        $generarPdf                  = new generarPdf();
       
        $generarPdf->generarPagareColocacion($titulo, $contenido, $numeroPagare, $documento, 'I' );*/

/*
        $dataRadicado = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfid', 3)->first();   
        $nombreAsociado             = 'DIONISIO DE JESUS ANGARITA ANGARITA ';   
        $fechaLargaPrestamo         = '29 de AGOSTO del 2023';
        $numeroPagare               = '55546';
        $documento                  = '88143913';
        $documentoAsociado          = number_format($documento, 0, ',', '.');
        $buscar                     = Array('nombreAsociado', 'numeroPagare', 'fechaLargaPrestamo');
        $remplazo                   = Array( $nombreAsociado, $numeroPagare, $fechaLargaPrestamo); 
        $titulo                     = str_replace($buscar,$remplazo,$dataRadicado->ingpdftitulo);
        $contenido                  = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido);
        $generarPdf                  = new generarPdf();       
        $generarPdf->generarCartaInstrucciones($titulo, $contenido, $numeroPagare, $documento, 'I' );*/


            // Ejemplo de uso
    $fechaActual = Carbon::now();
    //2024-03-03
    $fechaActual = '2023-11-02';
 $fechaNueva = $this->obtenerFechaPagoCuota('2023-12-02');
echo $fechaNueva->format('Y-m-d');

    $tabla = "<table border='1'>
            <tr>
                <th>Cuota</th>
                <th>Fecha</th>
                <th>Abono a Capital</th>
                <th>Valor Cuota</th>
                <th>Abono a Intereses</th>
            </tr>";

            for ($mes = 1; $mes <= 12; $mes++) {

                $fechaNueva = $this->obtenerFechaPagoCuota($fechaActual);
                $fechaNueva = $fechaNueva->format('Y-m-d');
                $tabla .= "<tr>
                        <td>$mes</td>
                        <td>$fechaNueva </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";

                $fechaActual = $fechaNueva ;
            }

          echo   $tabla .= "</table>";


/*
       
        $lineaCredito        = 'EMERGENCIA PERSONAL ';
        $asociado            = 'MAYERLY PAOLA CASTRO PAEZ';
        $descripcionCredito  = 'Cunplir con pagos no relacionados en el dia de hoy';
        $valorSolicitado     = 2000000; 
        $tasaNominal         = 1.3; 
        $plazoMensual        = 12;
        $generarPdf          = new generarPdf();
        $generarPdf->simuladorCredito($lineaCredito, $asociado, $descripcionCredito, $valorSolicitado, $tasaNominal, $plazoMensual);

        
        // Parámetros del préstamo
        $montoPrestamo = 2000000; // Monto del préstamo
        $tasaInteresMensual = 1.3; // Tasa de interés nominal mensual (1.3%)
        $plazo = 12; // Plazo en meses
        
        // Generar la tabla de liquidación
        /*$tablaLiquidacion = $this->generarTablaLiquidacion($montoPrestamo, $tasaInteresMensual, $plazo);
             


        echo $tablaLiquidacion;

       

        function redonderarCienMasCercano($valor){
            return round($valor/100.0,0)*100;
        }


        function calculcularValorCuota($montoPrestamo, $tasaInteresMensual, $plazo) {
            $tasaInteresMensual = $tasaInteresMensual / 100; // Convertir la tasa a formato decimal
            $denominador        = 1 - pow(1 + $tasaInteresMensual, -$plazo);
            $valorCuota         = ($montoPrestamo * $tasaInteresMensual) / $denominador;
            return redonderarCienMasCercano($valorCuota);
        }
        
        $montoPrestamo = 2000000; // Monto del préstamo
        $tasaInteresMensual = 1.3; // Tasa de interés nominal mensual (1.3%)
        $plazo = 12; // Plazo en meses
        
        $cuotaMensual = calculcularValorCuota($montoPrestamo, $tasaInteresMensual, $plazo);
        echo "El valor de la cuota mensual es: $cuotaMensual";*/


        // Simulador de credito
/*
        $generales = new generales();

        $tasaNominal = '1.30';
        $plazoMensual = '12';
        $valorSolicitado = '2000000';
        $nombre  = 'Ramon salazar';
        $descripcion = 'Prueba';
        $valorCuota = $generales->calculcularValorCuotaMensual($valorSolicitado, $tasaNominal, $plazoMensual);

        PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);

        //Construccion del PDF
        PDF::AddPage('P', 'Letter');
        #Establecemos los márgenes izquierda, arriba y derecha: 
        PDF::SetMargins(20, 40 , 10);
        #Establecemos el margen inferior: 
        PDF::SetAutoPageBreak(true,35);
        PDF::Ln(30); 
        PDF::SetFont('helvetica','B',12);
        PDF::Cell(180,5,'GENERACIÓN DEL PLAN DE PAGO ',0,0,'C');      
	    PDF::Ln(12); 
		PDF::SetFont('helvetica','',11);
		PDF::Cell(45,4,'Línea de crédito:',0,0,'');
		PDF::Cell(45,4,$nombre,0,0,'');
		PDF::Ln(4);
		PDF::Cell(45,4,'Descripción:',0,0,'');
		PDF::MultiCell(0,4,$descripcion,0,'',0);  
		PDF::Cell(45,4,'Valor solicitado:',0,0,'');
		PDF::Cell(45,4,'$'.number_format($valorSolicitado,0,',','.'),0,0,'');
		PDF::Ln(4);
		PDF::Cell(45,4,'Plazo mensual:',0,0,'');
		PDF::Cell(45,4,$plazoMensual,0,0,'');
		PDF::Ln(4);
		PDF::Cell(45,4,'Cuota mensual:',0,0,'');
		PDF::Cell(45,4,'$'.number_format($valorCuota,0,',','.'),0,0,'');
		PDF::Ln(4);
		PDF::Cell(45,4,'Tasa nominal mensual:',0,0,'');
        PDF::Cell(45,4,number_format($tasaNominal,1,',','.').'%',0,0,'');
        PDF::Ln(4);
		PDF::Cell(45,4,'Tasa efectiva anual:',0,0,'');
		PDF::Cell(45,4,number_format($generales->calcularTasaEfectivaAnual($tasaNominal), 2,',','.').'%',0,0,'');	
		
		PDF::Ln(12);
		PDF::SetFont('helvetica','',8);
		PDF::Cell(180,4,'* Los valores resultantes de esta simulación, son informativos, aproximados y podrán variar de acuerdo a las políticas',0,0,'');
		PDF::Ln(12);
		PDF::SetFont('helvetica','',11);
		PDF::Cell(180,4,'Tabla de liquidación:',0,0,'');
		PDF::Ln(6);
		PDF::SetFillColor(231,231,231);//color de fondo
		PDF::SetDrawColor(0);//color linea
		PDF::SetFont('helvetica','B',11);//texto del contenido de la tabla	
		PDF::Cell(12,5,'Nº',1,0,'C',true);


        PDF::Cell(42,5,'Abono Capital',1,0,'C',true);
        PDF::Cell(42,5,'Abono Intereses',1,0,'C',true);
		PDF::Cell(42,5,'Valor Cuota',1,0,'C',true);
        PDF::Cell(42,5,'Saldo Capital',1,0,'C',true);

        /*PDF::Cell(42,5,'Saldo Capital',1,0,'C',true);
		PDF::Cell(42,5,'Abono Capital',1,0,'C',true);
		PDF::Cell(42,5,'Valor Cuota',1,0,'C',true);
		PDF::Cell(42,5,'Abono Intereses',1,0,'C',true);*/

		/*PDF::Ln();
		PDF::SetFont('helvetica','',11);

        $saldoCapital = $valorSolicitado;       
        for ($numeroCuota = 1; $numeroCuota <= $plazoMensual; $numeroCuota++) {
            $valorInteres = $generales->calcularValorInteresMensula($saldoCapital, $tasaNominal);      
            $abonoCapital = round($valorCuota - $valorInteres, 0);

            if ($saldoCapital < $valorCuota) {
                $abonoCapital = $saldoCapital;
                $valorCuota   = $saldoCapital + $valorInteres;
            }

            $saldoCapital -= $abonoCapital;
            
            PDF::Cell(12, 5, $numeroCuota, 1, 0, 'C', false);
            PDF::Cell(42, 5, '$' . number_format($abonoCapital, 0, '.', ','), 1, 0, 'R');
            PDF::Cell(42, 5, '$' . number_format($valorInteres, 0, '.', ','), 1, 0, 'R');
            PDF::Cell(42, 5, '$' . number_format($valorCuota, 0, '.', ','), 1, 0, 'R');
            PDF::Cell(42, 5, '$' . number_format($saldoCapital, 0, '.', ','), 1, 0, 'R');

            /*PDF::Cell(42, 5, '$' . number_format($saldoCapital, 0, '.', ','), 1, 0, 'R');
            PDF::Cell(42, 5, '$' . number_format($abonoCapital, 0, '.', ','), 1, 0, 'R');
            PDF::Cell(42, 5, '$' . number_format($valorCuota, 0, '.', ','), 1, 0, 'R');
            PDF::Cell(42, 5, '$' . number_format($valorInteres, 0, '.', ','), 1, 0, 'R');*/
            /*PDF::Ln();
        }
       
     
     	$fecahaActual = str_replace(":", "-", date('Y-m-d-h:m:s'));
        PDF::output('Formulario_Solicitud_Credito_'.$fecahaActual.'.pdf', 'I');  
  


        

        /*$rutaCarpeta       = public_path().'/archivos/radicacion/documentoEntrante/2023';
        $nombreArchivoPdf = '913_autorizacion.pdf';
        $dataCopias    = [];

        $dataRadicado = DB::table('radicaciondocumentoentrante as rde')
                        ->select('rde.radoenfechahoraradicado  as fechaRadicado', DB::raw("CONCAT(rde.radoenanio,'-', rde.radoenconsecutivo) as consecutivo"),
                                'd.depenombre as dependencia','u.usuaalias as usuario', 'prd.peradocorreo  as correo',    'rde.radoenasunto as asunto',
                                DB::raw('(SELECT COUNT(radoedid) AS radoedid FROM radicaciondocentdependencia WHERE radoenid = rde.radoenid) AS totalCopias'))
                        ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                        ->join('dependencia as d', 'd.depeid', '=', 'rde.depeid')
                        ->join('usuario as u', 'u.usuaid', '=', 'rde.usuaid')
                        ->where('rde.radoenid', 11)->first();

                if($dataRadicado->totalCopias > 0){
                $dataCopias    =  DB::table('radicaciondocentdependencia as rded')
                            ->select('d.depenombre','d.depecorreo')
                            ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                            ->where('rded.radoenid', 11)->get();
                }

                $generarPdf->radicarDocumentoExterno($rutaCarpeta, $nombreArchivoPdf, $dataRadicado, $dataCopias, true);

                dd("verificado");
     
        try {       

           $sourcePdf = public_path('prueba.pdf');

            // Crea una instancia de FPDI
            $pdf = new FPDI();
    
            // Agrega la página del archivo PDF fuente al PDF actual
            $pageId = $pdf->setSourceFile($sourcePdf);
            $pdf->AddPage();
            $tplId = $pdf->importPage($pageId);
            $pdf->useTemplate($tplId);
    
            // Opcional: puedes agregar tu contenido o modificaciones aquí
            // Por ejemplo, agregar texto
            $pdf->SetFont('Arial', '', 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY(10, 10);
            $pdf->Write(0, '2222 Este es un PDF generado con FPDI y TCPDF. ....');
    
            // Genera la salida del PDF
            $pdf->Output('output.pdf', 'I');

            //return true;
		} catch (Exception $e) {
           // return false;
		}*/

    }

    function obtenerFechaPagoCuota($fecha) {
        $fecha      = Carbon::parse($fecha);
        $nuevaFecha = $fecha->addDays(30);

        // Verificar si la fecha resultante es 28 o 29 de febrero para ajustar al 01 de abril
		if (($fecha->day === 28 || $fecha->day === 29) && $fecha->month === 2) {
            $nuevaFecha->setMonth(3);
            $nuevaFecha->setDay(1);
        }

        // Verificar si la fecha resultante es el último día del mes para ajustar al 01 sigiente
        /*if ($fecha->day == $fecha->daysInMonth) {
            $nuevaFecha->addDay();
            $nuevaFecha->day = 1;
        }*/

        dd($fecha->format('Y-m-d'));
        return $fecha;
    }
    

    /*function obtenerFechaPagoCuota($fecha) {
        $fechaProcesar = Carbon::parse($fecha); 

        // Sumar 30 días a la fecha actual
        $fecha = $fechaProcesar->addDays(30);

      //var_dump($fecha->format('Y-m-d'));
        
        
        // Verificar si la fecha resultante es 28 o 29 de febrero
       if (($fechaProcesar->day === 28 || $fechaProcesar->day === 29) && $fechaProcesar->month === 2) {
        var_dump("entra aca");
            // Ajustar a 01 de abril
            $fecha->setMonth(3);
            $fecha->setDay(1);
        }
        
        // Verificar si la fecha resultante es el último día del mes
         if ($fechaProcesar->day == $fechaProcesar->daysInMonth) {
             var_dump($fechaProcesar->day, $fechaProcesar->daysInMonth);
            // Ajustar a 01 del mes siguiente
            $fecha->addDay();
            $fecha->day = 1;
        }*
    
        return $fecha;
    }*/

    function sumar30Dias($fecha) {
        // Convertir la cadena de fecha a un objeto Carbon
        $fecha = Carbon::parse($fecha);
        
        // Sumar 30 días a la fecha actual
        $fecha = $fecha->addDays(30);
        
        // Validar si es 28 o 29 de febrero
        if (($fecha->day === 28 || $fecha->day === 29) && $fecha->month === 2) {
            // Ajustar a 01 de abril
            $fecha->setMonth(3);
            $fecha->setDay(1);
        }
        
        // Verificar si la fecha resultante es el último día del mes
        if ($fecha->day === $fecha->daysInMonth) {
            // Ajustar a 01 del mes siguiente
            $fecha->addDay();
            $fecha->day = 1;
        }
    
        return $fecha;
    }
    




    function calcularCuota($montoPrestamo, $tasaInteresMensual, $plazo) {
        $tasaInteresMensual = $tasaInteresMensual / 100; // Convertir la tasa a formato decimal
        $denominador        = 1 - pow(1 + $tasaInteresMensual, -$plazo);
        $valorCuota         = ($montoPrestamo * $tasaInteresMensual) / $denominador;
        return $valorCuota;
    }

    function redonderarCienMasCercano($valor){
        return round($valor/100.0,0)*100;
    }

    function generarTablaLiquidacion($montoPrestamo, $tasaInteresMensual, $plazo) {            
        $cuotaMensual = $this->calcularCuota($montoPrestamo, $tasaInteresMensual, $plazo);
  
        $tasaInteresMensual = $tasaInteresMensual / 100; // Convertir la tasa a formato decimal
           
        $tabla = "<table border='1'>
                    <tr>
                        <th>Mes</th>
                        <th>Saldo a Capital</th>
                        <th>Abono a Capital</th>
                        <th>Valor Cuota</th>
                        <th>Abono a Intereses</th>
                    </tr>";
    
        $saldo = $montoPrestamo;
        for ($mes = 1; $mes <= $plazo; $mes++) {
            $intereses    = $saldo * $tasaInteresMensual;
            $abonoCapital = $cuotaMensual - $intereses;
            $saldo        -= $abonoCapital;

            
            $saldo        = $this->redonderarCienMasCercano($saldo);
            $abonoCapital = $this->redonderarCienMasCercano($abonoCapital);
            $cuotaMensual = $this->redonderarCienMasCercano($cuotaMensual);
            $intereses    = $this->redonderarCienMasCercano($intereses);
           
      
            if($saldo < $abonoCapital){
                $saldo = $intereses;
				//$abonoCapital = $saldo;
				//$cuotaMensual = $saldo + $intereses;
			}

            /*	
            $valorInteres =  $generales->calcularValorInteres($saldoCapital, $tasaNominal);
			$abonoCapital = round($valorCuota - $valorInteres, 0);

			if($saldoCapital < $abonoCapital){
				$abonoCapital = $saldoCapital;
				$valorCuota = $saldoCapital + $valorInteres;
			}*/


            $tabla .= "<tr>
                        <td>$mes</td>
                        <td>$saldo</td>
                        <td>$abonoCapital</td>
                        <td>$cuotaMensual</td>
                        <td>$intereses</td>
                    </tr>";
        }
    
        $tabla .= "</table>";
    
        return $tabla;
    }


    public function Pdf()
    {



       /* '2023-12-01'
        '2023-12-31'*/

       // $fechaMesSiguiente =  $this->fechaMesSiguiente('2024-02-28');

       // dd($fechaMesSiguiente->format('Y-m-d'));

        //$fechaNueva = $this->obtenerFechaPagoCuotaMia('2023-12-30');

        $generales        = new generales();
        /*$fechaVencimiento = $generales->obtenerFechaMesSiguiente($generales->obtenerFechaInicialColocacion());      

        $dateDos = $generales->obtenerFechaInicialColocacion($fechaVencimiento->format('Y-m-d'));

        dd($fechaVencimiento->format('Y-m-d'), $dateDos->format('Y-m-d'));


        $fechaNueva = $this->obtenerFechaInicialColocacion('2024-02-03');

        dd($fechaNueva->format('Y-m-d'));


        dd($this->obtenerFechaPagoCuota('2024-11-28'));*/


        $fechaInicialColocacion = $generales->obtenerFechaInicialColocacion();
       
        $fechaVencimiento       = $generales->obtenerFechaMesSiguiente($fechaInicialColocacion);  

       // dd($fechaInicialColocacion->format('Y-m-d'), $fechaVencimiento->format('Y-m-d'));

        $fechaHoraActual  = Carbon::now();
        $fechaActual      = $fechaHoraActual->format('Y-m-d');

        $numeroDiasCambioFecha  = $generales->calcularDiasCambiosFechaDesembolso($fechaInicialColocacion, $fechaActual);
        dd($numeroDiasCambioFecha);

        $colocacion = DB::table('colocacionliquidacion as cl')
                        ->select('cl.colliqvalorcuota', 'cl.colliqfechavencimiento', 'cl.colliqnumerocuota', 'cl.colliqvalorcuota', 'c.colofechacolocacion',
                        'c.colotasa','c.colonumerocuota','c.colovalordesembolsado',
                            DB::raw('(SELECT COUNT(colliqid) FROM colocacionliquidacion as cl1 WHERE coloid = c.coloid AND colliqfechapago IS NULL) AS totalCuotasPorPagar'),
                            DB::raw('(SELECT COUNT(colliqid) FROM colocacionliquidacion as cl2 WHERE coloid = c.coloid AND colliqfechapago IS NOT NULL) AS totalCuotasPagadas'))
                        ->join('colocacion as c', 'c.coloid', '=', 'cl.coloid')
                        ->where('c.coloid', 4)
                        ->where('cl.colliqid', 45)
                        ->first();


        $montoPrestamo         = $colocacion->colovalordesembolsado;
        $tasaInteresMensual    = $colocacion->colotasa;
        $plazo                 = $colocacion->colonumerocuota;
        $fechaVencimiento      = $colocacion->colliqfechavencimiento;
        $valorCuota            = $colocacion->colliqvalorcuota;
        $fechaDesembolso       = $colocacion->colofechacolocacion;
        $interesMora           = 2;
        $numeroDiasCambioFecha = ($colocacion->colliqnumerocuota === '1' ) ? $this->calcularDiasCambiosFechaDesembolso($fechaDesembolso, $fechaVencimiento) : 0;

       // $numeroDiasCambioFecha =  0;


        $arrayInteresMensual = $this->calcularValorInteresDiario($montoPrestamo, $tasaInteresMensual, $fechaVencimiento, $interesMora, $numeroDiasCambioFecha);
        dd($arrayInteresMensual['valorIntereses'], $arrayInteresMensual['valorInteresMora'], $arrayInteresMensual['valorDescuento']);

        /*"valorIntereses" => 50000.0
        "valorInteresMora" => 0
        "valorDescuento" => 100.0

        /*$interesMensual = $montoPrestamo * ($tasaInteresMensual / 100);
        $interesMora   = $montoPrestamo * ($interesMora / 100);**/

       

       /* dd($interesMensual, ($interesMora / 30 ) * 1);


        dd($montoPrestamo * ($interesMora / 100));

        dd($valorCuota);

        //$valorSolicitado * ($tasaNominal / 100)
        

        $valorCuota = $this->calcularPagoCuotaMensualArray($montoPrestamo, $tasaInteresMensual, $plazo, $fechaVencimiento, $interesMora);

        dd($colocacion);
        /*
        
        $generarPdf = new generarPdf();
        $empresa              = $generarPdf->consultarEmpresa();
		$direccionEmpresa 	  = $empresa->emprdireccion;
		$ciudadEmpresa    	  = $empresa->muninombre;
		$barrioEmpresa    	  = $empresa->emprbarrio;
		$telefonoEmpresa  	  = $empresa->emprtelefonofijo;
		$celularEmpresa   	  = $empresa->emprtelefonocelular;
		$urlEmpresa       	  = $empresa->emprurl;
		$nombreEmpresa        = $empresa->emprnombre;
		$lemaEmpresa          = $empresa->emprlema;
		$siglaEmpresa         = $empresa->emprsigla;
		$nit                  = $empresa->nit;
		$personeriaJuridica   = $empresa->emprpersoneriajuridica;
		$logoEmpresa          = $empresa->emprlogo;
        
        $conceptoComprobante = 'Documento de preuba';

       


        $fechaHoraActual = Carbon::now();
        $fechaActual     = $fechaHoraActual->format('Y-m-d');
        $idUsuario       = Auth::id();
        $agenciaId       = auth()->user()->agenid;
        $cajaId          = auth()->user()->cajaid;

        $fechaActual     = '2024-01-24';
        $agenciaId       = 101;
        $cajaId          = 1;
        $idUsuario       = 2;

        $nombreUsuario      = 'RAMÓN DAVID SALAZAR RINCON';
        $nuemeroComprobante = '20240001';
        $fechaComprobante   = '2024-01-24';
        $nombreAgencia      = 'PRINCIPAL';
        $numeroCaja         = '01';
        $titulo             = 'Comprobante contable número '.$nuemeroComprobante;


        /*Schema::create('comprobantecontable', function (Blueprint $table) {
            $table->bigIncrements('comconid')->unsigned()->comment('Identificador de la tabla comprobante contable');
            $table->bigInteger('movcajid')->unsigned()->comment('Identificador del movimiento caja');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario');
            $table->smallInteger('agenid')->unsigned()->comment('Identificador de la agencia');
            $table->tinyInteger('cajaid')->unsigned()->comment('Identificador de la caja');
            $table->year('comconanio', 4)->comment('Año en el cual se registra el comprobante contable');
            $table->string('comconconsecutivo', 5)->comment('Consecutivo del comprobante contable asignado por cada año');
            $table->dateTime('comconfechahora')->comment('Fecha y hora en la cual se crea el comprobante contable');
            $table->string('comcondescripcion', 1000)->comment('Descripción del comprobante contable');
            $table->dateTime('comconfechahoracierre')->nullable()->comment('Fecha y hora en la cual se cierra el comprobante contable');
            $table->string('comconestado', 1)->default('A')->comment('Estado del comprobante contable');*/

        /*$comprobantecontable = DB::table('comprobantecontable as cc')
                            ->select(DB::raw('DATE(cc.comconfechahora) as fechaComprobante'), 
                            DB::raw("CONCAT(cc.comconanio, cc.comconconsecutivo) as numeroComprobante"),
                            'cc.comcondescripcion', 'a.agennombre', 'c.cajanumero')
                            ->join('agencia as a', 'a.agenid', '=', 'cc.agenid')
                            ->join('caja as c', 'c.cajaid', '=', 'cc.cajaid')
                            ->whereDate('cc.comconfechahora', $fechaActual)
                            ->where('cc.usuaid', $idUsuario)
                            ->where('cc.agenid', $agenciaId)
                            ->where('cc.cajaid', $cajaId)
                            ->first();



        $moviemientosContables = DB::table('cuentacontable as cc')
        ->select(DB::raw('DATE(ccd.cocodefechahora) as fechaMovimiento'), 'cc.cueconid','cc.cueconnombre', 'cc.cueconcodigo','mc.cajaid', 'cct.agenid', 'mc.usuaid',
            DB::raw("(SELECT COALESCE(SUM(ccd.cocodemonto), 0)
                FROM comprobantecontabledetalle as ccd
                INNER JOIN cuentacontable as cc1 ON cc1.cueconid = ccd.cueconid
                INNER JOIN comprobantecontable as cct1 ON cct1.comconid = ccd.comconid
                INNER JOIN movimientocaja as mc1 ON mc1.movcajid = cct1.movcajid
                WHERE cc1.cueconnaturaleza = 'D'
                AND cc1.cueconid = cc.cueconid
                AND mc1.cajaid = mc.cajaid
                AND cct1.agenid = cct.agenid
                AND mc1.usuaid = mc.usuaid
                AND DATE(mc1.movcajfechahoraapertura) =  '$fechaActual'
            ) AS valorDebito"),
            DB::raw("(SELECT COALESCE(SUM(ccd.cocodemonto), 0)
                FROM comprobantecontabledetalle as ccd
                INNER JOIN cuentacontable as cc1 ON cc1.cueconid = ccd.cueconid
                INNER JOIN comprobantecontable as cct1 ON cct1.comconid = ccd.comconid
                INNER JOIN movimientocaja as mc1 ON mc1.movcajid = cct1.movcajid
                WHERE cc1.cueconnaturaleza = 'C'
                AND cc1.cueconid = cc.cueconid
                AND mc1.cajaid = mc.cajaid
                AND cct1.agenid = cct.agenid
                AND mc1.usuaid = mc.usuaid
                AND DATE(mc1.movcajfechahoraapertura) =  '$fechaActual'
            ) AS valorCredito")
        )
        ->join('comprobantecontabledetalle as ccd', 'ccd.cueconid', '=', 'cc.cueconid')
        ->join('comprobantecontable as cct', 'cct.comconid', '=', 'ccd.comconid')
        ->join('movimientocaja as mc', function ($join) {
            $join->on('mc.movcajid', '=', 'cct.movcajid');
            $join->on('mc.usuaid', '=', 'cct.usuaid');
        })
        ->whereDate('mc.movcajfechahoraapertura', $fechaActual)
        ->where('mc.usuaid', $idUsuario)
        ->where('cct.agenid', $agenciaId)
        ->where('mc.cajaid', $cajaId)
        ->groupBy(DB::raw('DATE(ccd.cocodefechahora)'), 'cc.cueconid', 'cc.cueconnombre', 'cc.cueconcodigo', 'mc.cajaid', 'cct.agenid', 'mc.usuaid')
        ->orderBy('cc.cueconid')
        ->get();

        $arrayDatos = [ 
            "nombreUsuario"       => 'RAMÓN DAVID SALAZAR RINCON',
            "nuemeroComprobante"  => $comprobantecontable->numeroComprobante,
            "fechaComprobante"    => $comprobantecontable->fechaComprobante,
            "nombreAgencia"       => $comprobantecontable->agennombre,
            "numeroCaja"          => $comprobantecontable->cajanumero,
            "conceptoComprobante" => $comprobantecontable->comcondescripcion,
            "mensajeImpresion"    => 'Documento impreso el dia '.$fechaHoraActual,
            "metodo"              => 'I'
        ];


        $generarPdf->generarComprobanteContable($arrayDatos, $moviemientosContables);



/*        

        $generarPdf->headerDocumentoHorizontal($nombreEmpresa, $siglaEmpresa, $personeriaJuridica, $nit, $logoEmpresa);
		$generarPdf->footerDocumentoHorizontal($direccionEmpresa, $barrioEmpresa, $telefonoEmpresa, $celularEmpresa, $urlEmpresa, $fechaActual);

        PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('ERP '.$siglaEmpresa);
		PDF::SetSubject($titulo);
		PDF::SetKeywords('Certificado, documento,'.$siglaEmpresa.', '.$titulo);
        PDF::SetTitle($titulo);

        PDF::SetProtection(array('copy'), '', null, 0, null);
		PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
		PDF::SetMargins(20, 30 , 20);
		PDF::AddPage('L', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(20); 
		PDF::SetFont('helvetica','B',12);
		PDF::Ln(16);
		PDF::Cell(254,5,'COMPROBANTE CONTABLE',0,0,'L'); 
		PDF::Ln(8);
        PDF::SetFont('helvetica','',10);
		PDF::Cell(38,4,'Número comprobante: ',0,0,'L');
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(52,4,$nuemeroComprobante,0,0,'L'); 
        PDF::SetFont('helvetica','',10);
        PDF::Cell(15,4,'Fecha: ',0,0,'L');
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(45,4,$fechaComprobante,0,0,'L');
        PDF::SetFont('helvetica','',10);
        PDF::Cell(18,4,'Agencia: ',0,0,'L');
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(68,4,$nombreAgencia,0,0,'L');
        PDF::SetFont('helvetica','',10);
        PDF::Cell(12,4,'Caja: ',0,0,'L');        
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(8,4,$numeroCaja,0,0,'L');
        PDF::Ln(4);
        PDF::SetFont('helvetica','',10); 
        PDF::Cell(38,5,'Concepto: ',0,0,'L'); 
        PDF::MultiCell(216, 0, $conceptoComprobante."\n", 0, 'J', 0);        
		PDF::SetFont('helvetica','',12); 
		PDF::Ln(8);
        PDF::Cell(30,4,'Código ','RB',0,'L');
        PDF::Cell(166,4,'Cuenta ','RB',0,'L');
        PDF::Cell(30,4,'Débito ','RB',0,'C');
        PDF::Cell(30,4,'Crédito ','RB',0,'C');
        PDF::Ln(5);

        $valorTotalDebito  = 0;
        $valorTotalCredito = 0;
        foreach($arrayDatos as $datos){
            $valorTotalDebito  += $datos->valorDebito;
            $valorTotalCredito += $datos->valorCredito;
            PDF::Cell(30,4,$datos->cueconcodigo,'R',0,'L');
            PDF::Cell(166,4,substr(mb_strtolower($datos->cueconnombre,'UTF-8') , 0, 83) ,'R',0,'L');
            PDF::Cell(30,4,'$'.number_format($datos->valorDebito,0,',','.'),'R',0,'R');
            PDF::Cell(30,4,'$'.number_format($datos->valorCredito,0,',','.'),'R',0,'R');
            PDF::Ln(5);
        }

        PDF::Ln(5);
        PDF::Cell(196,4,"Totales: ",0,0,'R');
        PDF::Cell(30,4,'$'.number_format($valorTotalDebito,0,',','.'),'TR',0,'R');
        PDF::Cell(30,4,'$'.number_format($valorTotalCredito,0,',','.'),'T',0,'R');

        PDF::Ln(24);
        PDF::Cell(80,4,$nombreUsuario,0,0,'L');
        PDF::Ln(6);
        PDF::Cell(80,4,"Preparó ",'T',0,'L');
        PDF::Cell(7,4,"",0,0,'C');
        PDF::Cell(80,4,"Revisó ",'T',0,'L');
        PDF::Cell(7,4,"",0,0,'R');
        PDF::Cell(80,4,"Aprobó ",'T',0,'L');



        PDF::output($titulo.'.pdf', 'I');


/*
        $generales  = new generales();  
        $generarPdf = new generarPdf();
        $arrayDatos = [ 
                        "numeroContratoEspecial"       => '45400830220230693',
                        "numeroContratoCompletoUno"    => '454008302202306930781',
                        "numeroContratoCompletoDos"    => '454008302202306930782',
                        "numeroContrato"               => '0693',
                        "numeroExtracto"               => '0781', 
                        "nombreContratante"            => 'COLEGIO CRISTIANO LUZ Y VIDA', 
                        "documentoContratante"         => '37313214_8',
                        "objetoContrato"               => 'La prestación del servicio de transporte de los estudiantes entre el lugar de residencia y el establecimiento educativo u otros destinos que se requieran en razón de las actividades programadas por el plantel educativo, según el decreto 0348 del 245 de febrero de 2015',
                        "origenContrato"               => 'OCAÑA ( N DE S)',
                        "destinoContrato"              => 'CASCO URBANO DE OCAÑA', 
                        "descripcionRecorrido"         => 'CASCO URBANO DE OCAÑA',
                        "convenioContrato"             => 'X',
                        "consorcioContrato"            => '',
                        "unionTemporal"                => '',
                        "nombreUnionTemporal"          => '',
                        "placaVehiculo"                => 'TTR122',
                        "modeloVehiculo"               => '2013',
                        "marcaVehiculo"                => 'NISSAN',
                        "claseVehiculo"                => 'MICROBUS',
                        "numeroInternoVehiculo"        => '486',
                        "tarjetaOperacionVehiculo"     => '282487',
                        "nombreContratante"            => 'ELIZABETH LOPEZ BARBOSA',
                        "documentoContratante"         => '37313214',
                        "direccionContratante"         => 'BARRIO EL CENTRO',
                        "telefonoContratante"          => '3103006860',
                        "firmaGerente"                 => 'archivos/persona/5036123/firma_5036123.png',
                        "nombreGerente"                => 'LUIS MANUEL ASCANIO CLARO',
                        "documentoGerente"             => '37.336.963',
                        "valorContrato"                => '1,500,000',
                        "fechaInicialContrato"         => $generales->formatearFechaContratoServicioEspecial('2023-11-01'),
                        "fechaFinalContrato"           => $generales->formatearFechaContratoServicioEspecial('2023-11-30'),
                        "descripcionServicoContratado" => 'DOS (2) vehículo(s) con número(s) interno(s) 473, 486 con 16, 16 puestos',
                        "idCifrado"                    => '123',
                        "metodo"                       => 'I'
                    ];

        $arrayVigenciaContrato = [];
        $fechaInicio = [
                        "dia"  => '01',
                        "mes"  => '11',
                        "anio" => '2023',
                    ];        
        $fechaFin = [
                    "dia"  => '30',
                    "mes"  => '11',
                    "anio" => '2023',
                ];        
        array_push($arrayVigenciaContrato, $fechaInicio, $fechaFin);

        $arrayConductores  = [];
        $conductor = [
                    "nombreCompleto" => 'ELAIN MACHADO DOMINGUEZ ELAIN MACHADO DOMINGUEZ',
                    "documento"      => '88284528',
                    "numeroLicencia" => '88284528',
                    "vigencia"       => '2025-10-04',
                    ]; 
        array_push($arrayConductores, $conductor);

        $conductor = [
                        "nombreCompleto" => 'HUBERNEY SALAZAR AMAYA',
                        "documento"      => '10648419387',
                        "numeroLicencia" => '10648419387',
                        "vigencia"       => '2025-09-26',
                        ]; 
        array_push($arrayConductores, $conductor);

        $conductor = [
                        "nombreCompleto" => 'DEIVER MORA GARCIA',
                        "documento"      => '1977858',
                        "numeroLicencia" => '1977858',
                        "vigencia"       => '2025-02-08',
                        ]; 
       array_push($arrayConductores, $conductor);

        $generarPdf->contratoServicioEspecial($arrayDatos, $arrayVigenciaContrato, $arrayConductores);

        /*PDF::setHeaderCallback(function($pdf) use ($idCifrado, $logoEmpresa) {
            $idCifrado = '123';
            $logoEmpresa = '890505424_logoHacaritama.png';

            $style = array(
				'border'        => 0,
				'vpadding'      => 'auto',
				'hpadding'      => 'auto',
				'fgcolor'       => array(0,0,0),
				'bgcolor'       => false,
				'module_width'  => 1,
				'module_height' => 1
			);

            $url = asset('verificar/contrato/servicio/especial/'.urlencode($idCifrado));
            PDF::write2DBarcode($url, 'QRCODE,H', 10, 6, 30, 30, $style, 'N');
            PDF::Image('images/logoColombiaPotenciaVida.jpg',50,12,70,14);
            PDF::Image('images/logoSuperTransporte.png',140,12,26,14);
            PDF::Image('archivos/logoEmpresa/'.$logoEmpresa,170,9,26,26);
		});*/


        /*PDF::SetAuthor('IMPLESOFT');
		PDF::SetCreator('Hacatitama');
		PDF::SetSubject("Formato único de extracto del contrato del servicio público de transporte terrestre automotor especial Nº ");
		PDF::SetKeywords('Formato, contrato, servicio público ');
        PDF::SetTitle("Formato contrato del servicio público de transporte terrestre ");
        PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
        PDF::SetMargins(10, 30 , 10);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(32); 
		PDF::SetFont('helvetica','B',12);
		PDF::Ln(4);
        PDF::Cell(26, 4,"", 0, 0,'C'); 
        PDF::MultiCell(140, 4, "FORMATO ÚNICO DE EXTRACTO DEL CONTRATO DEL SERVICIO PÚBLICO DE TRANSPORTE TERRESTRE AUTOMOTOR ESPECIAL Nº 454008302202306930781", 0, 'C', 0);
        PDF::Ln(4);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(130,5,'RAZON SOCIAL DE LA EMPRESA DE TRANSPORTE ESPECIAL','LTR',0,'L'); 
        PDF::Cell(60,5,'NIT','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(130,5,'COOPERATIVA DE TRANSPORTADORES HACARITAMA','LBR',0,'L'); 
        PDF::Cell(60,5,'890505424-7','LBR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(130,5,'CONTRATO:  0693','LBR',0,'L'); 
        PDF::Cell(60,5,'EXTRACTO:  0781','LBR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(130,5,'CONTRATANTE','LTR',0,'L'); 
        PDF::Cell(60,5,'NIT/CC','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(130,5,'COLEGIO CRISTIANO LUZ Y VIDA','LBR',0,'L'); 
        PDF::Cell(60,5,'37313214_8','LBR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(190,5,'OBJETO DEL CONTRATO','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(190, 5, "La prestación del servicio de transporte de los estudiantes entre el lugar de residencia y el establecimiento educativo u otros destinos que se requieran en razón de las actividades programadas por el plantel educativo, según el decreto 0348 del 245 de febrero de 2015.", 'LTR', 'J', 0);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(30,5,'ORIGEN','LTR',0,'L');
        PDF::SetFont('helvetica','',9); 
        PDF::Cell(160,5,'OCAÑA ( N DE S)','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(30,5,'DESTINO','LTR',0,'L');
        PDF::SetFont('helvetica','',9); 
        PDF::Cell(160,5,'CASCO URBANO DE OCAÑA','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','B',9);
		PDF::Cell(190,5,'DESCRIPCION DEL RECORRIDO','LTR',0,'L'); 
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::MultiCell(190, 5, "CASCO URBANO DE OCAÑA", 'LTR', 'L', 0);
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(30,5,'CONVENIO:','LTR',0,'L');
        PDF::Cell(8,5,'X','LTR',0,'C');
        PDF::Cell(30,5,'CONSORCIO:','LTR',0,'L');
        PDF::Cell(8,5,' ','LTR',0,'C');
        PDF::Cell(34,5,'UNION TEMPORAL:','LTR',0,'L');
        PDF::Cell(8,5,' ','LTR',0,'C');
        PDF::Cell(72,5,'CON:','LTR',0,'L');
        PDF::Ln(5);
        PDF::Cell(190,5,'VIGENCIA DEL CONTRATO','LTR',0,'C');
        PDF::Ln(5);

        PDF::Cell(47.5,5,'FECHA INICIAL','LTR',0,'C');
        PDF::Cell(47.5,5,'DIA','LTR',0,'C');
        PDF::Cell(47.5,5,'MES','LTR',0,'C');
        PDF::Cell(47.5,5,'AÑO','LTR',0,'C');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(47.5,5,'','LTR',0,'C');
        PDF::Cell(47.5,5,'01','LTR',0,'C');
        PDF::Cell(47.5,5,'11','LTR',0,'C');
        PDF::Cell(47.5,5,'2023','LTR',0,'C');
        PDF::Ln(5);

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(47.5,5,'FECHA INICIAL','LTR',0,'C');
        PDF::Cell(47.5,5,'DIA','LTR',0,'C');
        PDF::Cell(47.5,5,'MES','LTR',0,'C');
        PDF::Cell(47.5,5,'AÑO','LTR',0,'C');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(47.5,5,'','LTR',0,'C');
        PDF::Cell(47.5,5,'30','LTR',0,'C');
        PDF::Cell(47.5,5,'11','LTR',0,'C');
        PDF::Cell(47.5,5,'2023','LTR',0,'C');
        PDF::Ln(5);

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(190,5,'CARACTERISTICAS DEL VEHICULO','LTR',0,'C');
        PDF::Ln(5);        
        
        PDF::Cell(47.5,5,'PLACA','LTR',0,'C');
        PDF::Cell(47.5,5,'MODELO','LTR',0,'C');
        PDF::Cell(47.5,5,'MARCA','LTR',0,'C');
        PDF::Cell(47.5,5,'CLASE','LTR',0,'C');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(47.5,5,'TTR122','LTR',0,'C');
        PDF::Cell(47.5,5,'2013','LTR',0,'C');
        PDF::Cell(47.5,5,'NISSAN','LTR',0,'C');
        PDF::Cell(47.5,5,'MICROBUS','LTR',0,'C');
        PDF::Ln(5);

        PDF::Cell(95,5,'NÚMERO INTERNO','LTR',0,'C');
        PDF::Cell(95,5,'TARJETA DE OPERACIÓN','LTR',0,'C');
        PDF::Ln(5);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(95,5,'486','LTR',0,'C');
        PDF::Cell(95,5,'282487','LTR',0,'C');
        PDF::Ln(5);

        //Conductor 1
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(30, 5,'DATOS DEL','LTR',0,'L');
        PDF::Cell(65,5,' NOMBRE Y APELLIDO','LTR',0,'C');
        PDF::Cell(26,5,'CEDULA ','LTR',0,'C');
        PDF::Cell(40,5,'Nº DE LICENCIA','LTR',0,'C');
        PDF::Cell(29,5,'VIGENCIA','LTR',0,'C');
        PDF::Ln(5);
        PDF::Cell(30, 5,'CONDUCTOR 1','LTR',0,'L');
        PDF::SetFont('helvetica','',9);
        PDF::Cell(65,5,'ELAIN MACHADO DOMINGUEZ','LTR',0,'L');
        PDF::Cell(26,5,'88284528 ','LTR',0,'C');
        PDF::Cell(40,5,'88284528','LTR',0,'C');
        PDF::Cell(29,5,'2025-10-04','LTR',0,'C');
        PDF::Ln(5);

        //Conductor 2
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(30, 5,'DATOS DEL','LTR',0,'L');
        PDF::Cell(65,5,' NOMBRE Y APELLIDO','LTR',0,'C');
        PDF::Cell(26,5,'CEDULA ','LTR',0,'C');
        PDF::Cell(40,5,'Nº DE LICENCIA','LTR',0,'C');
        PDF::Cell(29,5,'VIGENCIA','LTR',0,'C');
        PDF::Ln(5);
        PDF::Cell(30, 5,'CONDUCTOR 1','LTR',0,'L');
        PDF::SetFont('helvetica','',9);
        PDF::Cell(65,5,'HUBERNEY SALAZAR AMAYA','LTR',0,'L');
        PDF::Cell(26,5,'1064841938 ','LTR',0,'C');
        PDF::Cell(40,5,'1064841938','LTR',0,'C');
        PDF::Cell(29,5,'2025-09-26','LTR',0,'C');
        PDF::Ln(5);

        //Conductor 3
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(30, 5,'DATOS DEL','LTR',0,'L');
        PDF::Cell(65,5,' NOMBRE Y APELLIDO','LTR',0,'C');
        PDF::Cell(26,5,'CEDULA ','LTR',0,'C');
        PDF::Cell(40,5,'Nº DE LICENCIA','LTR',0,'C');
        PDF::Cell(29,5,'VIGENCIA','LTR',0,'C');
        PDF::Ln(5);
        PDF::Cell(30, 5,'CONDUCTOR 1','LTR',0,'L');
        PDF::SetFont('helvetica','',9);
        PDF::Cell(65,5,'DEIVER MORA GARCIA','LTR',0,'L');
        PDF::Cell(26,5,'1977858 ','LTR',0,'C');
        PDF::Cell(40,5,'1977858','LTR',0,'C');
        PDF::Cell(29,5,'2025-02-08','LTR',0,'C');
        PDF::Ln(5);

        PDF::Cell(190,5,'','T',0,'C');
        PDF::Ln(5);

        PDF::SetFont('helvetica','B',9);
        PDF::Cell(30, 5,'RESPONSABLE','LTR',0,'L');
        PDF::Cell(65,5,'NOMBRE Y APELLIDO','LTR',0,'C');
        PDF::Cell(26,5,'CÉDULA ','LTR',0,'C');
        PDF::Cell(40,5,'DIRECCIÓN','LTR',0,'C');
        PDF::Cell(29,5,'TELÉFONO','LTR',0,'C');
        PDF::Ln(5);
        PDF::Cell(30, 5,'CONTRATANTE','LTR',0,'L');
        PDF::SetFont('helvetica','',9);
        PDF::Cell(65,5,'ELIZABETH LOPEZ BARBOSA','LTR',0,'L');
        PDF::Cell(26,5,'37313214 ','LTR',0,'C');
        PDF::Cell(40,5,'BARRIO EL CENTRO','LTR',0,'C');
        PDF::Cell(29,5,'3103006860','LTR',0,'C');
        PDF::Ln(5);
        PDF::Cell(95, 7,'','LTR',0,'L');
        PDF::Cell(95, 7,'','LTR',0,'L');
        PDF::Ln(7);
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(95, 6,'La Ondina via a Rio de Oro - Ocaña N. de S.','LR',0,'C');
        PDF::Cell(95, 6,'','R',0,'L');
        PDF::Ln(6);
        PDF::Cell(95, 6,'Tel. 607-5611012 - Cel: 314 2197149 - 317 6686929','LR',0,'C');
        PDF::Cell(95, 6,'','R',0,'L');
        PDF::Ln(6);
        PDF::SetFont('helvetica','',9);
        PDF::Cell(95, 6,'cootranshacaritama@hotmail.com.','LR',0,'C');
        PDF::Cell(95, 6,'(Ley 527 de 1999, Decreto 2364 de 2012)','R',0,'C');
        PDF::Ln(6);
        PDF::Cell(95, 6,'','LRB',0,'C');
        PDF::SetFont('helvetica','B',9);
        PDF::Cell(95, 6,'FIRMA Y SELLO GERENTE DEL CONTRATO.','RB',0,'C');
        
       
        //Inicia proceso de la hoja dos
        $dataRadicado = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', 'fichaTecnica')->first();
        $numeroContratoServicioEspecial = '454008302202306930782';  
        $buscar       = Array('numeroContratoServicioEspecial');
        $remplazo     = Array($numeroContratoServicioEspecial);
        $contenido                   = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido);  
        PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);
        PDF::AddPage('P', 'Letter');
        PDF::SetMargins(20, 30 , 20);
		PDF::Ln(4);
        PDF::SetFont('helvetica', '', 10);
		PDF::writeHTML($contenido, true, false, true, false, '');

        //Inicia proceso de la hoja 5
        $dataRadicado = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', 'contratoTransporteEspecial')->first();    
        $buscar       = Array('numeroContratoServicioEspecial');
        $remplazo     = Array($numeroContratoServicioEspecial);
        $numeroContratoServicioEspecial = '454008302202306930782';  
        $contenido                   = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido);  
        PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);
        PDF::AddPage('P', 'Letter');
        PDF::SetMargins(20, 30 , 20);
  
		PDF::Ln(4);
        PDF::SetFont('helvetica', 'B', 12);
        PDF::Cell(190, 6,'CONTRATO DE TRANSPORTE ESPECIAL 45400830220230693',0,0,'C');
        
        PDF::Ln(12);
        PDF::SetFont('helvetica', '', 10);
		PDF::writeHTML($contenido, true, false, true, false, '');

        PDF::Ln(32);
        PDF::SetFont('helvetica','B',10);
        PDF::Cell(90, 6,'COLEGIO CRISTIANO LUZ Y VIDA',0,0,'C');
        PDF::Cell(10, 6,'',0,0,'C');
        PDF::Cell(90, 6,'LUIS MANUEL ASCANIO CLARO',0,0,'C');
        PDF::Ln(6);

        PDF::Cell(90, 6,'NIT/C.C: 37313214_8',0,0,'C');
        PDF::Cell(10, 6,'',0,0,'C');
        PDF::SetFont('helvetica','',10);
        PDF::Cell(90, 6,'EL CONTRATISTA',0,0,'C');
        PDF::Ln(6);
        PDF::Cell(90, 6,'EL CONTRATANTE',0,0,'C');


        //Inicia proceso de la hoja 6	       
    


        //Inicia proceso de la hoja tres
        PDF::SetPrintHeader(true);
		PDF::SetPrintFooter(true);
        PDF::AddPage('P', 'Letter');

        PDF::SetFont('helvetica','B',14);
		PDF::Ln(24);
		PDF::Cell(175,5,"hola 3",0,0,'C'); 

        // Salida del PDF
        PDF::Output();



        /*
        PDF::SetPrintHeader(false);
		PDF::SetPrintFooter(false);
		PDF::SetMargins(24, 36 , 20);
		PDF::AddPage('P', 'Letter');
		PDF::SetAutoPageBreak(true, 30);
		PDF::SetY(32); 
		PDF::SetFont('helvetica','B',14);
		PDF::Ln(24);
		PDF::Cell(175,5,"hola",0,0,'C'); 
        PDF::Ln(24);

        PDF::Cell(15,5,"hola",0,0,'C'); 

        PDF::MultiCell(145, 4, "FORMATO ÚNICO DE EXTRACTO DEL CONTRATO DEL SERVICIO PÚBLICO DE TRANSPORTE TERRESTRE AUTOMOTOR ESPECIAL Nº 454008302202306930781", 1, 'C', 0);

        PDF::output("formato.pdf", 'I');*/


    /*// Ejemplo de uso
    $fechaActual = Carbon::now();
    //2024-03-03
    $fechaActual = '2023-10-30';
    $fechaNueva = $this->obtenerFechaPagoCuota('2023-10-30');
    echo $fechaNueva->format('Y-m-d');



  $tabla = "<table border='1'>
            <tr>
                <th>Cuota</th>
                <th>Fecha</th>
                <th>Abono a Capital</th>
                <th>Valor Cuota</th>
                <th>Abono a Intereses</th>
            </tr>";

            for ($mes = 1; $mes <= 20; $mes++) {

                $fechaNueva = $this->obtenerFechaPagoCuota($fechaActual);
                $fechaNueva = $fechaNueva->format('Y-m-d');
                $tabla .= "<tr>
                        <td>$mes</td>
                        <td>$fechaNueva </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";

                $fechaActual = $fechaNueva ;
            }

         echo   $tabla .= "</table>";/**/
    }


    function obtenerFechaPagoCuota11($fecha) {
        $fecha        = Carbon::parse($fecha);
        $nuevaFecha        = Carbon::parse($fecha);
        $diaFecha     = $fecha->day;
        $mesFecha     = $fecha->month;
        $ultimoDiaMes = $fecha->daysInMonth;
       // $totalDiasAdicionar = ($mesFecha === 2) ? 29 : (($ultimoDiaMes === 31) ? $ultimoDiaMes : 30);


        $totalDiasAdicionar = ($ultimoDiaMes === 30 ) ? 30 :  $ultimoDiaMes;

        //$totalDiasAdicionar = ($totalDiasAdicionar === 31) ? 30 : $totalDiasAdicionar;
        


        //dd($fecha->format('Y-m-d'));
        
        // Sumar 30 días a la fecha actual
        $nuevaFecha = $fecha->addDays($totalDiasAdicionar);

       // dd($fecha->format('Y-m-d'), $nuevaFecha->format('Y-m-d'));
        //$nuevaFecha = $fecha->addDays(30);
/*
       if ($diaFecha === 1) {
            $nuevaFecha->addDay();
        } */

        // Verificar si es 28 o 29 de febrero
        if (($diaFecha === 28 || $diaFecha === 29) && $mesFecha === 2) {
            //var_dump("debe entrar1");
            // Ajustar a 01 de abril
            $nuevaFecha->setMonth(4);
            $nuevaFecha->setDay(1);
        }
    
        // Verificar si la fecha resultante es el último día del mes
       /* if ($diaFecha === $ultimoDiaMes) {
            //var_dump("debe entrar");

            $nuevaFecha->day = 1;
            $nuevaFecha->addMonth();


            // Ajustar a 01 del mes siguiente
           // $nuevaFecha->addDay();
           // $nuevaFecha->day = 1;
        }*/

       // dd( $nuevaFecha->day);
    
        // Verificar si la fecha actual es el último día de un mes de 31 días
       if ($nuevaFecha->day === 31) { // || $fecha->day === 31
        var_dump("debe entrar");
           // $nuevaFecha->setMonth($mesFecha + 1);
           // $nuevaFecha->setDay(1);

            // Ajustar a 01 del mes siguiente
            $nuevaFecha = $nuevaFecha->addDay(1);
            $nuevaFecha->day = 1;
        } 
    
        // Ajustar para evitar un día extra en febrero
        /*if ($fecha->month === 2 && ($fecha->day === 29 || ($fecha->day === 28 && !$fecha->isLeapYear()))) {
            $nuevaFecha->day = 1;
            $nuevaFecha->addMonth();
        }*/
    
        return $nuevaFecha;
    }


  
    function obtenerFechaPagoCuota2($fecha) {
        $fecha = Carbon::parse($fecha);
    
        // Sumar 30 días a la fecha actual
        $nuevaFecha = $fecha->addDays(($fecha->day === 2) ? 28 : 30);

        if ($fecha->day === 1) {
            $nuevaFecha->addDay();
        }       
    
        // Verificar si es 28 o 29 de febrero
        if (($fecha->day === 28 || $fecha->day === 29) && $fecha->month === 2) {
            var_dump("entra en febero");
            // Ajustar a 01 de abril
            $nuevaFecha->setMonth(4);
            $nuevaFecha->setDay(1);
        }
    
        // Verificar si la fecha actual es el último día del mes
        if ($fecha->day === $fecha->daysInMonth) {
            var_dump("entra en  2");
            // Ajustar a 01 del mes siguiente
            $nuevaFecha->addDay();
            $nuevaFecha->day = 1;
        }
    
        // Verificar si la fecha actual es el último día de un mes de 31 días
        if ($fecha->day === 31) {
            var_dump("entra en el 31");
            // Ajustar a 01 del mes siguiente
            $nuevaFecha->addDay();
            $nuevaFecha->day = 1;
        }

        // Ajustar para evitar un día extra en febrero
        if ($fecha->day === 29 && $fecha->month === 2 && !$fecha->isLeapYear()) {
            $nuevaFecha->day = 1;
            $nuevaFecha->addMonth();
        }
    
        return $nuevaFecha;
    }




/*
    function obtenerFechaPagoCuota($fecha) {
        $fecha      = Carbon::parse($fecha);
        $nuevaFecha = $fecha->addDays(30);

        if ($fecha->day === 1) {
            $nuevaFecha->addDay();
        }        

        // Verificar si la fecha resultante es 28 o 29 de febrero para ajustar al 01 de abril
		if (($fecha->day === 28 || $fecha->day === 29) && $fecha->month === 2) {
            $nuevaFecha->setMonth(3);
            $nuevaFecha->setDay(1);
        }

        // Verificar si la fecha resultante es el último día del mes para ajustar al 01 sigiente
        if ($fecha->day == $fecha->daysInMonth) {
            $nuevaFecha->addDay();
            $nuevaFecha->day = 1;
        }

        //dd($fecha->format('Y-m-d'));
        return $fecha;
    }
    

    /*function obtenerFechaPagoCuota($fecha) {
        $fechaProcesar = Carbon::parse($fecha); 

        // Sumar 30 días a la fecha actual
        $fecha = $fechaProcesar->addDays(30);

      //var_dump($fecha->format('Y-m-d'));
        
        
        // Verificar si la fecha resultante es 28 o 29 de febrero
       if (($fechaProcesar->day === 28 || $fechaProcesar->day === 29) && $fechaProcesar->month === 2) {
        var_dump("entra aca");
            // Ajustar a 01 de abril
            $fecha->setMonth(3);
            $fecha->setDay(1);
        }
        
        // Verificar si la fecha resultante es el último día del mes
         if ($fechaProcesar->day == $fechaProcesar->daysInMonth) {
             var_dump($fechaProcesar->day, $fechaProcesar->daysInMonth);
            // Ajustar a 01 del mes siguiente
            $fecha->addDay();
            $fecha->day = 1;
        }*
    
        return $fecha;
    }*/

    function sumar30Dias11($fecha) {
        // Convertir la cadena de fecha a un objeto Carbon
        $fecha = Carbon::parse($fecha);
        
        // Sumar 30 días a la fecha actual
        $fecha = $fecha->addDays(30);
        
        // Validar si es 28 o 29 de febrero
        if (($fecha->day === 28 || $fecha->day === 29) && $fecha->month === 2) {
            // Ajustar a 01 de abril
            $fecha->setMonth(3);
            $fecha->setDay(1);
        }
        
        // Verificar si la fecha resultante es el último día del mes
        if ($fecha->day === $fecha->daysInMonth) {
            // Ajustar a 01 del mes siguiente
            $fecha->addDay();
            $fecha->day = 1;
        }
    
        return $fecha;
    } 

    function calcularCuota21($montoPrestamo, $tasaInteresMensual, $plazo) {
        $tasaInteresMensual = $tasaInteresMensual / 100; // Convertir la tasa a formato decimal
        $denominador        = 1 - pow(1 + $tasaInteresMensual, -$plazo);
        $valorCuota         = ($montoPrestamo * $tasaInteresMensual) / $denominador;
        return $valorCuota;
    }



    function generarTablaLiquidacion21($montoPrestamo, $tasaInteresMensual, $plazo) {            
        $cuotaMensual = $this->calcularCuota($montoPrestamo, $tasaInteresMensual, $plazo);
  
        $tasaInteresMensual = $tasaInteresMensual / 100; // Convertir la tasa a formato decimal
           
        $tabla = "<table border='1'>
                    <tr>
                        <th>Mes</th>
                        <th>Saldo a Capital</th>
                        <th>Abono a Capital</th>
                        <th>Valor Cuota</th>
                        <th>Abono a Intereses</th>
                    </tr>";
    
        $saldo = $montoPrestamo;
        for ($mes = 1; $mes <= $plazo; $mes++) {
            $intereses    = $saldo * $tasaInteresMensual;
            $abonoCapital = $cuotaMensual - $intereses;
            $saldo        -= $abonoCapital;

            
            $saldo        = $this->redonderarCienMasCercano($saldo);
            $abonoCapital = $this->redonderarCienMasCercano($abonoCapital);
            $cuotaMensual = $this->redonderarCienMasCercano($cuotaMensual);
            $intereses    = $this->redonderarCienMasCercano($intereses);
           
      
            if($saldo < $abonoCapital){
                $saldo = $intereses;
				//$abonoCapital = $saldo;
				//$cuotaMensual = $saldo + $intereses;
			}

            /*	
            $valorInteres =  $generales->calcularValorInteres($saldoCapital, $tasaNominal);
			$abonoCapital = round($valorCuota - $valorInteres, 0);

			if($saldoCapital < $abonoCapital){
				$abonoCapital = $saldoCapital;
				$valorCuota = $saldoCapital + $valorInteres;
			}*/


            $tabla .= "<tr>
                        <td>$mes</td>
                        <td>$saldo</td>
                        <td>$abonoCapital</td>
                        <td>$cuotaMensual</td>
                        <td>$intereses</td>
                    </tr>";
        }
    
        $tabla .= "</table>";
    
        return $tabla;
    }

    public function contrato()
    {
        $generales      = new generales();  
        $generarPdf     = new generarPdf();


        $vehiculoId = 2;
        $vehiculocontrato = DB::table('vehiculocontrato as vc')
                                    ->select('vc.vehconid','vc.vehconfechainicial','vc.vehconfechafinal', DB::raw("CONCAT(vc.vehconanio, vc.vehconnumero) as numeroContrato"),
                                    'v.vehinumerointerno','v.vehiplaca','v.timoveid',
                                    'tmv.timovecuotasostenimiento','tmv.timovedescuentopagoanticipado','tmv.timoverecargomora')
                                    ->join('vehiculo as v', 'v.vehiid', '=', 'vc.vehiid')
                                    ->join('tipomodalidadvehiculo as tmv', 'tmv.timoveid', '=', 'v.timoveid')
                                    ->where('vc.vehiid', $vehiculoId)
                                    ->where('vc.vehconfechafinal', function ($query) use ($vehiculoId) {
                                        $query->selectRaw('MAX(vehconfechafinal)')
                                            ->from('vehiculocontrato')
                                            ->where('vehiid', $vehiculoId);
                                    })
                                    ->first();

        $vehiculoContratoAsociados = DB::table('vehiculocontratoasociado as vca')
                                    ->select('p.persdocumento', 'p.persdireccion', 'p.perscorreoelectronico','p.persnumerocelular', DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                    p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                                   ->join('asociado as a', 'a.asocid', '=', 'vca.asocid')
                                   ->join('persona as p', 'p.persid', '=', 'a.persid')
                                   ->where('vca.vehconid', $vehiculocontrato->vehconid)
                                   ->get();

         $empresa    = DB::table('empresa as e')->select('p.persdocumento', DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"),
                                'me.muninombre as nombreMunicipioExpedicion')
                               ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                               ->join('municipio as me', function($join)
                               {
                                   $join->on('me.munidepaid', '=', 'p.persdepaidexpedicion');
                                   $join->on('me.muniid', '=', 'p.persmuniidexpedicion'); 
                               })
                               ->where('e.emprid', 1)->first();

        if($vehiculocontrato->timoveid === 7){
            $idInformacionPdf = 'contratoModalidadEspecial';
        }else  if($vehiculocontrato->timoveid === 'I'){
            $idInformacionPdf = 'contratoModalidadIntermunicipal';
        }else  if($vehiculocontrato->timoveid === 'C'){
            $idInformacionPdf = 'contratoModalidadColectivo';
        } else {
            $idInformacionPdf = 'contratoModalidadMixto';
        }

        $informacionPDF                 = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', $idInformacionPdf)->first(); 
        $fechaFirmaContrato             = $generales->formatearFecha($vehiculocontrato->vehconfechainicial);
        $cuotaSostenimientoAdmon        = number_format($vehiculocontrato->timovecuotasostenimiento, 0, ',', '.') ;
        $descuentoPagoAnualAnticipado   = $vehiculocontrato->timovedescuentopagoanticipado;
        $recargoCuotaSostenimientoAdmon = $vehiculocontrato->timoverecargomora;
        $nombreGerente                  = $empresa->nombrePersona;
        $documentoGerente               = number_format($empresa->persdocumento, 0, ',', '.');
        $ciudadExpDocumentoGerente      = $empresa->nombreMunicipioExpedicion;;
        $numeroContrato                 = $vehiculocontrato->numeroContrato;
        $fechaContrato                  = $generales->formatearFechaContrato($vehiculocontrato->vehconfechainicial);
        
        $identificacionAsociado         = '';
        $nombreAsociado                 = '';
        $direccionAsociado              = '';
        $telefonoAsociado               = '';
        $correoAsociado                 = '';
        $nombreGerenteFirma             = $nombreGerente;
        $documentoGerenteFirma          = 'C.C. '.$documentoGerente;
        $arrayFirmas                    = [];
        foreach($vehiculoContratoAsociados as $vehiculoContratoAsociado){
            $identificacionAsociado .= number_format($vehiculoContratoAsociado->persdocumento, 0, ',', '.').', ';
            $nombreAsociado         .= $vehiculoContratoAsociado->nombreAsociado.', ';
            $direccionAsociado      .= $vehiculoContratoAsociado->persdireccion.', ';
            $telefonoAsociado       .= ($vehiculoContratoAsociado->persnumerocelular !== null ) ? $vehiculoContratoAsociado->persnumerocelular.', ': '';
            $correoAsociado         .= ($vehiculoContratoAsociado->perscorreoelectronico !== null ) ? $vehiculoContratoAsociado->perscorreoelectronico.', ': ''; 

            $firmasContrato = [
                    "nombreGerente"     => $nombreGerenteFirma,
                    "nombreAsociado"    => $vehiculoContratoAsociado->nombreAsociado,
                    "documentoGerente"  => $documentoGerenteFirma,
                    "documentoAsociado" => 'C.C. '.number_format($vehiculoContratoAsociado->persdocumento, 0, ',', '.'),
                    "direccionAsociado" => $vehiculoContratoAsociado->persdireccion
                ];

            array_push($arrayFirmas, $firmasContrato);
            $nombreGerenteFirma     = '';
            $documentoGerenteFirma  = '';
        }

        $arrayDatos = [ "titulo"           => 'Contrato número '.$numeroContrato,
                        "numeroContrato"   => $numeroContrato,
                        "placaVehiculo"    => $vehiculocontrato->vehiplaca,
                        "numeroInterno"    => $vehiculocontrato->vehinumerointerno,
                        "propietarios"     => substr($nombreAsociado, 0, -2),
                        "identificaciones" => substr($identificacionAsociado, 0, -2),
                        "direcciones"      => substr($direccionAsociado, 0, -2),
                        "telefonos"        => substr($telefonoAsociado, 0, -2),
                        "correos"          => substr($correoAsociado, 0, -2),
                        "metodo"           => 'I'
                    ]; 

        $buscar     = Array('documentoGerente', 'nombreGerente', 'ciudadExpDocumentoGerente', 'cuotaSostenimientoAdmon','descuentoPagoAnualAnticipado',
                            'recargoCuotaSostenimientoAdmon','fechaFirmaContrato','fechaContrato'
                            );
        $remplazo   = Array($documentoGerente, $nombreGerente, $ciudadExpDocumentoGerente, $cuotaSostenimientoAdmon, $descuentoPagoAnualAnticipado,
                            $recargoCuotaSostenimientoAdmon, $fechaFirmaContrato, $fechaContrato 
                            ); 
        $contenido  = str_replace($buscar,$remplazo,$informacionPDF->ingpdfcontenido);         
 
        $generarPdf->contratoVehiculo($arrayDatos, $contenido, $arrayFirmas, $idInformacionPdf);
    }


    function calcularPagoCuotaMensual1($montoPrestamo, $tasaInteresMensual, $plazo, $fechaVencimiento, $interesMora)
	{
		$fechaActual = Carbon::now();
		
		if ($fechaVencimiento->lt($fechaActual)) {// Calcular días de mora
			$diasMora = $fechaActual->diffInDays($fechaVencimiento);
			// Cobrar intereses de mora
			$interesMensual = $tasaInteresMensual / 100;
			$interesDiario = $interesMensual / Carbon::daysInMonth($fechaVencimiento->year, $fechaVencimiento->month);
			$interesMoraTotal = $interesDiario * $montoPrestamo * $diasMora;
			// Sumar intereses de mora al monto del préstamo
			$montoPrestamo += $interesMoraTotal;
		} else {
			// Calcular días adicionales
			$diasAdicionales = $fechaVencimiento->diffInDays($fechaActual);
			// Cobrar intereses adicionales
			$interesAdicional = 0.02; // 2% diario
			$interesAdicionalTotal = $interesAdicional * $montoPrestamo * $diasAdicionales;
			// Sumar intereses adicionales al monto del préstamo
			$montoPrestamo += $interesAdicionalTotal;
		}

		if ($tasaInteresMensual == 0.0) {
			$valorCuota = $montoPrestamo / $plazo;
		} else {
			$tasaInteresMensual = $tasaInteresMensual / 100; // Convertir la tasa a formato decimal
			$denominador = 1 - pow(1 + $tasaInteresMensual, -$plazo);
			$valorCuota = ($montoPrestamo * $tasaInteresMensual) / $denominador;
		}

		return $this->redonderarMilSiguiente($valorCuota);
	}

	function calcularPagoCuotaMensual ($montoPrestamo, $tasaInteresMensual, $plazo, $fechaVencimiento, $interesMora) {

		$fechaActual    = now();
		$diasDiferencia = $fechaActual->diffInDays($fechaVencimiento);
	
		// Calcular la cuota mensual sin considerar la mora
		if ($tasaInteresMensual == 0.0) {
			$valorCuota = $montoPrestamo / $plazo;
		} else {
			$tasaInteresMensualDecimal = $tasaInteresMensual / 100; // Convertir la tasa a formato decimal
			$denominador = 1 - pow(1 + $tasaInteresMensualDecimal, -$plazo);    
			$valorCuota = ($montoPrestamo * $tasaInteresMensualDecimal) / $denominador;
		}

		if ($fechaVencimiento->lt($fechaActual)) {	// Calcular los días de mora
			$diasMora = $fechaActual->diffInDays($fechaVencimiento);			
			$interesMoraTotal = $interesMora / 100 * $montoPrestamo * $diasMora;
			$montoPrestamo += $interesMoraTotal;
		} else { // Si la fecha de vencimiento es mayor a la fecha actual
			// Calcular los intereses adicionales
			$interesAdicional = 0.02 / 100; // 2% diario
			
			// Calcular los intereses adicionales
			$interesAdicionalTotal = $interesAdicional * $montoPrestamo * $diasDiferencia;
	
			// Sumar intereses adicionales al monto del préstamo
			$montoPrestamo += $interesAdicionalTotal;
		}
		
		// Redondear y devolver el valor de la cuota
		return $this->redonderarMilSiguiente($valorCuota);
	}

	function calcularPagoCuotaMensualFinal($montoPrestamo, $tasaInteresMensual, $plazo, $fechaVencimiento, $interesMora) {
		// Calcular la fecha actual
		$fechaActual = now();
	
		// Verificar si la fecha de vencimiento es anterior a la fecha actual
		if ($fechaVencimiento->lt($fechaActual)) {
			// Calcular los días de mora
			$diasMora = $fechaActual->diffInDays($fechaVencimiento);
			
			// Calcular el interés de mora
			$interesMensual = $interesMora / 100;
			$interesDiario = $interesMensual / Carbon::daysInMonth($fechaVencimiento->year, $fechaVencimiento->month);
			$interesMoraTotal = $interesDiario * $montoPrestamo * $diasMora;
	
			// Sumar el interés de mora al monto del préstamo
			$montoPrestamo += $interesMoraTotal;
		} elseif ($fechaVencimiento->gt($fechaActual)) {
			// Calcular la cuota mensual sin considerar la mora
			if ($tasaInteresMensual == 0.0) {
				$valorCuota = $montoPrestamo / $plazo;
			} else {
				$tasaInteresMensual = $tasaInteresMensual / 100; // Convertir la tasa a formato decimal
				$denominador = 1 - pow(1 + $tasaInteresMensual, -$plazo);    
				$valorCuota = ($montoPrestamo * $tasaInteresMensual) / $denominador;
			}
			return $this->redondearMilSiguiente($valorCuota);
		}
	
		// Redondear y devolver el valor de la cuota
		return $this->redondearMilSiguiente($montoPrestamo);
	}
}