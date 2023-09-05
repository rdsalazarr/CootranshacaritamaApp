<?php

namespace App\Util;

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
		//construyo la fecha en formato largo
		$separarFecha=explode('-',$fecha);
		$anyo=$separarFecha[0];
		$mes=$separarFecha[1];
		$dia=$separarFecha[2];

		$mes1 = $this->obtenerMes($mes);
		return $dia." de ".$mes1." de ".$anyo; 
	}

	//Obtiene el mes de la fecha
	function obtenerMes($mes){	
		if($mes=='01'){$mes1="enero";} 
		else if($mes=='02'){$mes1="febrero";} 
		else if($mes=='03'){$mes1="marzo";}
		else if($mes=='04'){$mes1="abril";}
		else if($mes=='05'){$mes1="mayo";}
		else if($mes=='06'){$mes1="junio";}
		else if($mes=='07'){$mes1="julio";}
		else if($mes=='08'){$mes1="agosto";}
		else if($mes=='09'){$mes1="septiembre";}
		else if($mes=='10'){$mes1="octubre";}
		else if($mes=='11'){$mes1="noviembre";}
		else if($mes=='12'){$mes1="diciembre";}
		return $mes1;
	}

	///2015-08-09 09:45:08
	function formatearFechaHora($fechaFor){
	  $separarfech=explode('-',$fechaFor);
	  $anyo=$separarfech[0];
	  $mes=$separarfech[1];
	  $dia=$separarfech[2];
	  $dia= substr($separarfech[2], 0, 2);
	  $hora= substr($separarfech[2], 3, 5);//no trae los segundos
		 if($mes=='01'){$mes1="enero ";} 
	     if($mes=='02'){$mes1="febrero ";} 
	     if($mes=='03'){$mes1="marzo ";}
		 if($mes=='04'){$mes1="abril ";}
		 if($mes=='05'){$mes1="mayo ";}
		 if($mes=='06'){$mes1="junio ";}
		 if($mes=='07'){$mes1="julio ";}
		 if($mes=='08'){$mes1="agosto ";}
		 if($mes=='09'){$mes1="septiembre ";}
		 if($mes=='10'){$mes1="octubre ";}
		 if($mes=='11'){$mes1="noviembre ";}
		 if($mes=='12'){$mes1="diciembre ";}
		return $dia." de ".$mes1." de ".$anyo." a las ".$hora; 
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

}