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
    
    public function Pdf()
    {  	

            // Ejemplo de uso
    $fechaActual = Carbon::now();
    //2024-03-03
    $fechaActual = '2023-10-30';
 $fechaNueva = $this->obtenerFechaPagoCuota('2024-01-30');
echo $fechaNueva->format('Y-m-d');


/*
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


    function obtenerFechaPagoCuota($fecha) {
        $fecha        = Carbon::parse($fecha);
        $nuevaFecha        = Carbon::parse($fecha);
        $diaFecha     = $fecha->day;
        $mesFecha     = $fecha->month;
        $ultimoDiaMes = $fecha->daysInMonth;
       // $totalDiasAdicionar = ($mesFecha === 2) ? 29 : (($ultimoDiaMes === 31) ? $ultimoDiaMes : 30);


        $totalDiasAdicionar = ($ultimoDiaMes === 30 ) ? 30 :  $ultimoDiaMes;
        
dd($totalDiasAdicionar);

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

}