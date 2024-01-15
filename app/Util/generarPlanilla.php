<?php

namespace App\Util;

use App\Util\convertirNumeroALetras;

use App\Util\generarPdf;
use App\Util\generales;
use Carbon\Carbon;
use DB, URL;

class generarPlanilla
{
    function servicioEspecial($coseesid, $metodo){
        $convertirNumeroALetras   = new convertirNumeroALetras();
		$generales  			  = new generales();  
		$generarPdf 			  = new generarPdf();
		$url        			  = URL::to('/');

		$contratoServicioEspecial = DB::table('contratoservicioespecial as cse')
										->select('cse.coseesid','cse.pecoseid','cse.ticossid','cse.coseesanio','cse.coseesconsecutivo','cse.coseesfechaincial','cse.coseesvalorcontrato',
												'cse.coseesfechafinal','cse.coseesorigen','cse.coseesdestino','cse.coseesdescripcionrecorrido','cse.coseesobservacion', 'cse.coseesnombreuniontemporal',
												'pcse.pecosedocumento',	'pcse.tipideid','pcse.pecosedireccion', 'pcse.pecosenumerocelular','p.persdocumento',
												DB::raw("CONCAT(pcse.pecoseprimernombre,' ',if(pcse.pecosesegundonombre is null ,'', pcse.pecosesegundonombre),' ',
												pcse.pecoseprimerapellido,' ',if(pcse.pecosesegundoapellido is null ,' ', pcse.pecosesegundoapellido)) as nombreContratante"),
												DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
												p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreGerente"),
												DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafirma ) as firmaGerente"),
												)
										->join('personacontratoservicioesp as pcse', 'pcse.pecoseid', '=', 'cse.pecoseid')
										->join('persona as p', 'p.persid', '=', 'cse.persidgerente')
										->where('cse.coseesid', $coseesid)->first();
		$anioContrato   = $contratoServicioEspecial->coseesanio;
		$numeroContrato = $contratoServicioEspecial->coseesconsecutivo;

		$contratoVehiculos   = DB::table('contratoservicioespecialvehi as csev')
								->select('csev.coseevid','csev.coseevextractoanio', 'csev.coseevextractoconsecutivo','v.vehiplaca','v.vehimodelo','v.vehinumerointerno', 'tmv.timavenombre', 'tv.tipvehnombre', 'tv.tipvehcapacidad','vto.vetaopnumero')
								->join('vehiculo as v', 'v.vehiid', '=', 'csev.vehiid')
								->join('tipomarcavehiculo as tmv', 'tmv.timaveid', '=', 'v.timaveid')
								->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
								->leftJoin('vehiculotarjetaoperacion as vto', function ($join) {
									$join->on('vto.vehiid', '=', 'v.vehiid')
										->where('vto.vetaopfechafinal', '=', DB::raw('(SELECT MAX(vetaopfechafinal) FROM vehiculotarjetaoperacion WHERE vehiid = v.vehiid)'));
								})
								->where('csev.coseesid', $coseesid)->get();
		$contador          = 0;
		$numerosInternos   = '';
		$capacidadVehiculo = '';
		foreach($contratoVehiculos as $contratoVehiculo){
			$numerosInternos .= $contratoVehiculo->vehinumerointerno.', ';
			$capacidadVehiculo .= $contratoVehiculo->tipvehcapacidad.', ';
			$contador ++;
		}

		$servicoContratado = ($contador > 1 ) ? $convertirNumeroALetras->valorEnLetras($contador).'('.$contador.') vehículo(s) con número(s) interno(s) '.substr($numerosInternos, 0, -2).' con '.substr($capacidadVehiculo, 0, -2).' puestos' : 'Un vehículo con número interno '. substr($numerosInternos, 0, -2).' de '.substr($capacidadVehiculo, 0, -2).' puestos';
		$objetoContrato    = 'La realización de un servicio de transporte expreso para trasladar a todas las personas que hacen parte del grupo desde un origen ';
		$objetoContrato    .= 'determinado en el presente contrato hasta el destino determinado en el presente contrato, según el decreto 0348 del 245 de febrero de 2015.';

		$arrayDatos = [ 
						"numeroContratoEspecial"       => '454008302'.$anioContrato.''.$numeroContrato,
						"numeroContrato"               => $numeroContrato,
						"nombreContratante"            => $contratoServicioEspecial->nombreContratante,
						"documentoContratante"         => number_format($contratoServicioEspecial->pecosedocumento,0,',','.'),
						"direccionContratante"         => $contratoServicioEspecial->pecosedireccion,
						"telefonoContratante"          => $contratoServicioEspecial->pecosenumerocelular,
						"objetoContrato"               => $objetoContrato,
						"origenContrato"               => $contratoServicioEspecial->coseesorigen,
						"destinoContrato"              => $contratoServicioEspecial->coseesdestino,
						"descripcionRecorrido"         => $contratoServicioEspecial->coseesdescripcionrecorrido,
						"valorContrato"                => number_format($contratoServicioEspecial->coseesvalorcontrato,0,',','.'),
						"fechaInicialContrato"         => $generales->formatearFechaContratoServicioEspecial($contratoServicioEspecial->coseesfechaincial),
						"fechaFinalContrato"           => $generales->formatearFechaContratoServicioEspecial($contratoServicioEspecial->coseesfechafinal),
						"descripcionServicoContratado" => $servicoContratado,
						"firmaGerente"                 => $contratoServicioEspecial->firmaGerente,
						"nombreGerente"                => $contratoServicioEspecial->nombreGerente,
						"documentoGerente"             => number_format($contratoServicioEspecial->persdocumento,0,',','.'),
						"idCifrado"                    => $contratoServicioEspecial->coseesid,
						"convenioContrato"             => ($contratoServicioEspecial->ticossid === 'CV') ? 'X' : '',
						"consorcioContrato"            => ($contratoServicioEspecial->ticossid === 'CS') ? 'X' : '',
						"unionTemporal"                => ($contratoServicioEspecial->ticossid === 'UT') ? 'X' : '',
						"nombreUnionTemporal"          => ($contratoServicioEspecial->ticossid === 'UT') ? $contratoServicioEspecial->coseesnombreuniontemporal : '',
						"observaciones"                => $contratoServicioEspecial->coseesobservacion,
						"metodo"                       => $metodo
					];

		$arrayVigenciaContrato = [];
		$fechaIncial           = explode('-',$contratoServicioEspecial->coseesfechaincial);
		$fechaFinal            = explode('-',$contratoServicioEspecial->coseesfechafinal);
		$fechaInicio 		   = [
									"dia"  => $fechaIncial[2],
									"mes"  => $fechaIncial[1],
									"anio" => $fechaIncial[0]
								];

		$fechaFin 				= [
									"dia"  => $fechaFinal[2],
									"mes"  => $fechaFinal[1],
									"anio" => $fechaFinal[0]
								];
		array_push($arrayVigenciaContrato, $fechaInicio, $fechaFin);

		$arrayConductores = DB::table('contratoservicioespecialcond as csec')
									->select('p.persdocumento as documento','cl.conlicnumero as numeroLicencia', 'cl.conlicfechavencimiento as vigencia',
										DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
										p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreCompleto")
									)
									->join('conductor as c', 'c.condid', '=', 'csec.condid')
									->join('persona as p', 'p.persid', '=', 'c.persid')
									->join('conductorlicencia as cl', function ($join) {
										$join->on('cl.condid', '=', 'c.condid')
											->where('cl.conlicfechavencimiento', '=', DB::raw('(SELECT MAX(conlicfechavencimiento) FROM conductorlicencia WHERE condid = c.condid)'));
									})
									->where('coseesid', $coseesid)
									->get();

		return $generarPdf->contratoServicioEspecial($arrayDatos, $arrayVigenciaContrato, $contratoVehiculos, $arrayConductores);
	}
}