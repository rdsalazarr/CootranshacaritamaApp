<?php

namespace App\Util;

use App\Util\convertirNumeroALetras;
use Carbon\Carbon;
use DB;

class generales
{
	//funcion para ordenar array
	function ordenarArray($datos, $label = [], $nombres =[] , $segundo = [], $tercero = [], $cuarto = [], $quinto = [])
	{
		$arreglo = [];
		foreach ($datos as $item) {
			foreach ($nombres as $nombre){
				$item[$nombre]=[];
			}

			foreach ($segundo as $item2) {
				if ($item[$label[0]] == $item2[$label[0]]) {
					array_push($item[$nombres[0]], $item2);
				}
			}

			foreach ($tercero as $item3) {
				if ($item[$label[1]] == $item3[$label[1]]) {
					array_push($item[$nombres[1]], $item3);
				}
			}

			foreach ($cuarto as $item4) {
				if ($item[$label[2]] == $item4[$label[2]]) {
					array_push($item[$nombres[2]], $item4);
				}
			}

			foreach ($quinto as $item5) {
				if ($item[$label[3]] == $item5[$label[3]]) {
					array_push($item[$nombres[3]], $item5);
				}
			}
		array_push($arreglo, $item);
		}

		return $arreglo;
	}

	//funicon que me quitas los caracteres especiales 
	function quitarCaracteres($url){
		$url = strtolower($url);
		$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ', '_');
		$repl = array('a', 'e', 'i', 'o', 'u', 'n', '-');
		$url = str_replace ($find, $repl, $url);
		$find = array(' ', '&', '\r\n', '\n', '+');
		$url = str_replace ($find, '-', $url);
		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
		$repl = array('', '-', '');
		$url = preg_replace ($find, $repl, $url);
		return $url;
	}

  	//Funcion para crear la fecha en formato largo
	public function formatearFecha($fecha){
		$separarFecha = explode('-',$fecha);
		$anyo         = $separarFecha[0];
		$mes          = $separarFecha[1];
		$dia          = $separarFecha[2];
		$mesEnLetra   = $this->obtenerMes($mes);
		return $dia." de ".$mesEnLetra." de ".$anyo; 
	}

	//Obtiene el mes de la fecha
	function obtenerMes($mes){
		$meses = [
			'01' => 'enero',
			'02' => 'febrero',
			'03' => 'marzo',
			'04' => 'abril',
			'05' => 'mayo',
			'06' => 'junio',
			'07' => 'julio',
			'08' => 'agosto',
			'09' => 'septiembre',
			'10' => 'octubre',
			'11' => 'noviembre',
			'12' => 'diciembre',
		];
		return $meses[$mes] ?? '';
	}

	//2015-08-09 09:45:08
	function formatearFechaHora($fechaFor){
		$separarfech = explode('-',$fechaFor);
		$anyo        = $separarfech[0];
		$mes         = $separarfech[1];
		$dia         = $separarfech[2];
		$dia         = substr($separarfech[2], 0, 2);
		$hora        = substr($separarfech[2], 3, 5);//no trae los segundos
		$mesEnLetra   = $this->obtenerMes($mes);
		return $dia." de ".$mesEnLetra." de ".$anyo." a las ".$hora; 
	}

	public function formatearFechaContrato($fecha){
		$convertirNumeroALetras = new convertirNumeroALetras();
		$separarFecha           = explode('-',$fecha);
		$anyo                   = $separarFecha[0];
		$mes                    = $separarFecha[1];
		$dia                    = $separarFecha[2];
		$mesEnLetra             = $this->obtenerMes($mes);
		$diaEnLetra             = $convertirNumeroALetras->valorEnLetras($dia);

		return $diaEnLetra.' ('.str_pad($dia, 2, "0", STR_PAD_LEFT).') días del mes de '.$mesEnLetra.' de '.$anyo;
	}

	public function formatearFechaLargaPagare($fecha){
		$separarFecha = explode('-',$fecha);
		$anyo         = $separarFecha[0];
		$mes          = $separarFecha[1];
		$dia          = $separarFecha[2];
		$mesEnLetra   = $this->obtenerMes($mes);
		return $dia." días del mes de ".$mesEnLetra." de ".$anyo; 
	}

	public function formatearFechaContratoServicioEspecial($fecha){
		$diasSemana = [
						1 => 'Lunes',
						2 => 'Martes',
						3 => 'Miércoles',
						4 => 'Jueves',
						5 => 'Viernes',
						6 => 'Sábado',
						7 => 'Domingo',
					];
		$separarFecha = explode('-',$fecha);
		$anyo         = $separarFecha[0];
		$mes          = $separarFecha[1];
		$dia          = $separarFecha[2];
		$numeroDia    = date('N', strtotime($fecha));
		$nombreDia    = $diasSemana[$numeroDia];
		$mesEnLetra   = $this->obtenerMes($mes);
		return $nombreDia.', '.$dia." de ".$mesEnLetra." de ".$anyo; 
	}

	function validarContrasena($contrasena) {
		$success = false;
		$message = '';
		// Verifica la longitud de la contraseña
		if (strlen($contrasena) < 8 || strlen($contrasena) > 20) {		
			$message = 'Debe tener entre 8 y 20 caracteres de longitud';
			return array($success, $message );
		}
	
		// Verifica si hay al menos una letra mayúscula
		if (!preg_match('/[A-Z]/', $contrasena)) {
			$message = 'Debe incluir al menos una letra mayúscula';
			return array($success, $message );
		}
	
		// Verifica si hay al menos una letra minúscula
		if (!preg_match('/[a-z]/', $contrasena)) {
			$message = 'Debe incluir al menos una letra minúscula';
			return array($success, $message );
		}
	
		// Verifica si hay al menos un número
		if (!preg_match('/[0-9]/', $contrasena)) {
			$message = 'Debe contener al menos un número';
			return array($success, $message );
		}
	
		// Verifica si hay al menos un carácter especial
		if (!preg_match('/[\!\@\#\$\%\^\&\*\(\)\-\_\+\=\[\]\{\}\|\:\;\,\.\<\>\/\?]/', $contrasena)) {
			$message = 'Debe incluir al menos un carácter especial o el carácter no esta soportado';
			return array($success, $message );
		}

		/*if (!preg_match('/[A-Z]/', $contrasena) || !preg_match('/[a-z]/', $contrasena) || !preg_match('/\d/', $contrasena) || !preg_match('/[\*\#\!]/', $contrasena)) {
			$message = 'No debe tener números ni letras consecutivas';
			return array($success, $message );
		}*/

		// Si pasa todas las validaciones, la contraseña es válida
		$success = true;
		return array($success, $message );
	}

	function obtenerConvocatoriaActa($lugar, $fecha, $hora){	
		$fechaGenerada = $this->formatearFecha($fecha);
		return " La proxima reunión se realizará en ".$lugar." el día ".$fechaGenerada ." a partir de ".$hora." horas. ";
	}

	public function obtenerFechaMaxima($tiempoRespuesta, $fechaProcesar){
        $listaFestivos = DB::table('festivo')->select('festfecha')
	    					->whereDate('festfecha', '>=', $fechaProcesar)
				            ->orderBy('festfecha')->get();
		$festivos = [];
		foreach ($listaFestivos as $fest) {
			$festivos[] = $fest->festfecha;
		}

		//Realizamos el proceso para asignar la posible fecha
		$fecha_procesar = date("Y-m-d",strtotime($fechaProcesar."+ 1 days"));	

		//Iniciamos a recorre para calcular la fecha de respuesta
		$contador = 1;
		for($i=0; $i<100; $i++){
		     $fecha_procesar = date("Y-m-d",strtotime($fecha_procesar."+ 1 days"));  
		     $verificarFecha = $this->validarFecha($fecha_procesar,  $festivos);

		     if($verificarFecha == 1){
		         $contador += 1;
		     }

		     if($tiempoRespuesta == $contador){       
		         break;//termine el ciclo
		     }
		 }

		return $fecha_procesar;
    }

	function validarFecha($fecha, $festivos) {
	    $dias = array('', 'Lunes','Martes','Miercoles','Jueves','Viernes','Sabado', 'Domingo');
	    $diaActual = $dias[date('N', strtotime($fecha))];

	    $diasValidos = array('Lunes','Martes','Miercoles','Jueves','Viernes');
	    $fechaValida = 0;
	    if(in_array($diaActual, $diasValidos)) {
	        $fechaValida = 1;
	    }
	    
	    if($fechaValida == 1){
	        if(in_array($fecha, $festivos)) { //No se puede asignar la fecha
	            $fechaValida = 0;
	        }
	    } 

	    return $fechaValida;
	}

	function reducirPesoPDF($archivoOriginal, $archivoReducido) {
		// Comando para reducir el peso del archivo PDF
		$comando = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$archivoReducido $archivoOriginal";
	
		// Ejecutar el comando
		exec($comando, $salida, $estado);

		return ($estado === 0) ? true : false;
	}

	function validarFechaVencimiento($fechaActual, $fechaVencimiento) {
        // Parsea las fechas a objetos Carbon
        $fechaActual      = Carbon::parse($fechaActual);
        $fechaVencimiento = Carbon::parse($fechaVencimiento);
    
        // Resta 10 días a la fecha actual
        $fechaPosterior = $fechaActual->copy()->addDays(10);
    
        // Comprueba si la fecha actual es mayor o igual a la fecha de vencimiento
        if ($fechaActual->greaterThanOrEqualTo($fechaVencimiento)) {
            return true; // Debe actualizar
        }
    
        // Comprueba si la fecha de vencimiento está entre la fecha posterior y la fecha actual
        if ($fechaVencimiento->between($fechaPosterior, $fechaActual)) {
            return true; // Debe actualizar
        }
    
        return false; // No debe actualizar
    }

	function calculcularValorCuotaMensual($montoPrestamo, $tasaInteresMensual, $plazo) {
		if ($tasaInteresMensual == 0.0) {
			$valorCuota = $montoPrestamo / $plazo;
		} else {
			$tasaInteresMensual = $tasaInteresMensual / 100; // Convertir la tasa a formato decimal
			$denominador        = 1 - pow(1 + $tasaInteresMensual, - $plazo);	
			$valorCuota         = ($montoPrestamo * $tasaInteresMensual) / $denominador;
		}
		return $this->redonderarMilSiguiente($valorCuota);
	}

	function calcularValorInteresMensual($valorSolicitado, $tasaNominal){
		return $this->redonderarCienMasCercano($valorSolicitado * ($tasaNominal / 100));
	}

	function calcularTasaEfectivaAnual($tasaNominalMensual) {
		$tasaNominalDecimal = $tasaNominalMensual / 100;
		$tea = pow(1 + $tasaNominalDecimal, 12) - 1;
		$tea *= 100;
		return $tea;
	}

	function redonderarMilSiguiente($valor){
		return ceil($valor/1000)*1000;
	}

	function redonderarCienMasCercano($valor){
		return round($valor/100.0,0)*100;
	}

	function obtenerFechaPagoCuota($fecha) {
		$fecha        = Carbon::parse($fecha);
        $nuevaFecha   = Carbon::parse($fecha);
        $diaFecha     = $fecha->day;
        $mesFecha     = $fecha->month;
        $ultimoDiaMes = $fecha->daysInMonth;

		$totalDiasAdicionar = ($ultimoDiaMes === 30 ) ? 30 :  $ultimoDiaMes;
		$nuevaFecha = $fecha->addDays($totalDiasAdicionar);

        // Verificar si la fecha resultante es 28 o 29 de febrero para ajustar al 01 de abril
		if (($diaFecha === 28 || $diaFecha === 29) && $mesFecha === 2) {            
            $nuevaFecha->setMonth(4);
            $nuevaFecha->setDay(1);
        }

        // Verificar si la fecha resultante es el último día del mes para ajustar al 01 sigiente
        /*if ($fecha->day == $fecha->daysInMonth) {
            $fecha->addDay();
            $fecha->day = 1;
        }*/

        return $nuevaFecha;
    }
}