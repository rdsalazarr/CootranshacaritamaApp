<?php

namespace App\Util;
use App\Util\generales;
use Auth, URL, DB;

class showTipoDocumental
{
	function acta($id)
	{
		$infodocumento =  DB::table('coddocumprocesoacta as cdpa') 
						->select('cdpa.codopaid as id', DB::raw("CONCAT(cdpa.codopaanio,' - ', cdpa.codopaconsecutivo) as consecutivogenerado"),
						'cdpa.codopaconsecutivo','cdpa.codopasigla','cdpa.codopaanio','cdpa.tipactid', 'cdpa.codopahorainicio', 'cdpa.codopahorafinal', 
						'cdpa.codopalugar', 'cdpa.codopaquorum','cdpa.codopaordendeldia', 'cdpa.codopainvitado', 'cdpa.codopaausente', 
						'cdpa.codopaconvocatoria', 'cdpa.codopaconvocatorialugar',	'cdpa.codopaconvocatoriafecha', 'cdpa.codopaconvocatoriahora','ta.tipactnombre',
						'cdp.tiesdoid','cdp.codoprid','cdp.codoprfecha','cdp.codoprnombredirigido','cdp.codoprcorreo',	'cdp.codoprcontenido',
						'cdp.codoprtieneanexo','cdp.codoprtienecopia','cdp.codoprsolicitafirma', 'cdp.codoprfirmado','cdp.coddocid',
						DB::raw("if(cdp.codoprtieneanexo = 1 ,'Sí', 'No') as tieneanexo"),
						DB::raw("if(cdp.codoprtienecopia = 1 ,'Sí', 'No') as tienecopia"),
						'ted.tiesdonombre as estado',
						'cd.depeid','cd.serdocid','cd.susedoid','cd.tipdocid','cd.tipmedid','cd.tiptraid','cd.tipdetid',
						'tdc.tipdoccodigo','sd.serdoccodigo', 'ssd.susedocodigo',
						'd.depenombre as dependencia', 'd.depecodigo', 'u.usuaalias as alias','d.depenombre',
						DB::raw('(SELECT COUNT(codopxid) AS codopxid FROM coddocumprocesoanexo WHERE codoprid = cdp.codoprid) AS totalAnexos'))
						->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpa.codoprid')
	  					->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
						->join('tipoestadodocumento as ted', 'ted.tiesdoid', '=', 'cdp.tiesdoid')
						->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
						->join('tipoacta as ta', 'ta.tipactid', '=', 'cdpa.tipactid')
						->join('seriedocumental as sd', 'sd.serdocid', '=', 'cd.serdocid')
						->join('subseriedocumental as ssd', function($join)
							{
								$join->on('ssd.susedoid', '=', 'cd.susedoid');
								$join->on('ssd.serdocid', '=', 'cd.serdocid'); 
							})
						->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
						->join('usuario as u', 'u.usuaid', '=', 'cd.usuaid')
						->where('cdpa.codopaid', $id)->first();

		$firmas = $this->obtenerFirmaDocumento($infodocumento->codoprid);

		return array ($infodocumento, $firmas);
	}

	function certificado($id)
	{
		$infodocumento =  DB::table('coddocumprocesocertificado as cdpc')
						->select('cdpc.codopcid as id','cdpc.tipedoid', DB::raw("CONCAT(cdpc.codopcanio,' - ', cdpc.codopcconsecutivo) as consecutivogenerado"),
						'cdpc.codopcconsecutivo','cdpc.codopcsigla','cdpc.codopcanio', 'cdpc.codopctitulo','cdpc.codopccontenidoinicial','cdp.tiesdoid',
						'tpd.tipedonombre','cdp.codoprid','cdp.codoprfecha','cdp.codoprnombredirigido','cdp.codoprcorreo',	'cdp.codoprcontenido',
						'cdp.codoprtieneanexo','cdp.codoprtienecopia','cdp.codoprsolicitafirma', 'cdp.codoprfirmado','cdp.coddocid',
						DB::raw("if(cdp.codoprtieneanexo = 1 ,'Sí', 'No') as tieneanexo"),
						DB::raw("if(cdp.codoprtienecopia = 1 ,'Sí', 'No') as tienecopia"),
						'ted.tiesdonombre as estado',
						'cd.depeid','cd.serdocid','cd.susedoid','cd.tipdocid','cd.tipmedid','cd.tiptraid','cd.tipdetid',
						'tdc.tipdoccodigo','sd.serdoccodigo', 'ssd.susedocodigo',
						'd.depenombre as dependencia', 'd.depecodigo', 'u.usuaalias as alias',
						DB::raw('(SELECT COUNT(codopxid) AS codopxid FROM coddocumprocesoanexo WHERE codoprid = cdp.codoprid) AS totalAnexos'))
						->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
	  					->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
						->join('tipoestadodocumento as ted', 'ted.tiesdoid', '=', 'cdp.tiesdoid')
						->join('tipopersonadocumental as tpd', 'tpd.tipedoid', '=', 'cdpc.tipedoid')
						->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
						->join('seriedocumental as sd', 'sd.serdocid', '=', 'cd.serdocid')
						->join('subseriedocumental as ssd', function($join)
							{
								$join->on('ssd.susedoid', '=', 'cd.susedoid');
								$join->on('ssd.serdocid', '=', 'cd.serdocid'); 
							})
						->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
						->join('usuario as u', 'u.usuaid', '=', 'cd.usuaid')
						->where('cdpc.codopcid', $id)->first();

		$firmas = $this->obtenerFirmaDocumento($infodocumento->codoprid);

		return array ($infodocumento, $firmas);
	}

	function circular($id)
	{
        $infodocumento =  DB::table('coddocumprocesocircular as cdpc')
						->select('cdpc.codoplid as id','cdpc.tipdesid', DB::raw("CONCAT(cdpc.codoplanio,' - ', cdpc.codoplconsecutivo) as consecutivogenerado"),
						'cdpc.codoplconsecutivo','cdpc.codoplsigla','cdpc.codoplanio', 'cdp.tiesdoid','td.tipdesnombre',
						'cdp.codoprid','cdp.codoprfecha','cdp.codoprnombredirigido','cdp.codoprcargonombredirigido','cdp.codoprasunto','cdp.codoprcorreo',
						'cdp.codoprcontenido','cdp.codoprtieneanexo','cdp.codoprtienecopia','cdp.codoprsolicitafirma',
						'cdp.codopranexonombre','cdp.codoprcopianombre', 'cdp.codoprfirmado','cdp.coddocid',
						DB::raw("if(cdp.codoprtieneanexo = 1 ,'Sí', 'No') as tieneanexo"),
						DB::raw("if(cdp.codoprtienecopia = 1 ,'Sí', 'No') as tienecopia"),
						'ted.tiesdonombre as estado',
						'cd.depeid','cd.serdocid','cd.susedoid','cd.tipdocid','cd.tipmedid','cd.tiptraid','cd.tipdetid',
						'tdc.tipdoccodigo','sd.serdoccodigo', 'ssd.susedocodigo',
						'd.depenombre as dependencia', 'd.depecodigo', 'u.usuaalias as alias',
						DB::raw('(SELECT COUNT(codopxid) AS codopxid FROM coddocumprocesoanexo WHERE codoprid = cdp.codoprid) AS totalAnexos'))
						->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
	  					->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
						->join('tipoestadodocumento as ted', 'ted.tiesdoid', '=', 'cdp.tiesdoid')
						->join('tipodespedida as td', 'td.tipdesid', '=', 'cdpc.tipdesid')						
						->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
						->join('seriedocumental as sd', 'sd.serdocid', '=', 'cd.serdocid')
						->join('subseriedocumental as ssd', function($join)
							{
								$join->on('ssd.susedoid', '=', 'cd.susedoid');
								$join->on('ssd.serdocid', '=', 'cd.serdocid'); 
							})
						->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
						->join('usuario as u', 'u.usuaid', '=', 'cd.usuaid')
						->where('cdpc.codoplid', $id)->first();

		$firmas            = $this->obtenerFirmaDocumento($infodocumento->codoprid);
		$copiaDependencias = $this->obtenerCopiaDocumento($infodocumento->codoprid);
	
		$anexosDocumento = DB::table('coddocumprocesoanexo as cdpa')
						  ->select('cdpa.codopxid as id','cdpa.codopxnombreanexooriginal as nombreOriginal','cdpa.codopxnombreanexoeditado as nombreEditado',
						  'cdpa.codopxrutaanexo as rutaAnexo', 'cdpc.codoplsigla as sigla','cdpc.codoplanio as anio',DB::raw("CONCAT('1') as idFolder"),
						  	DB::raw("CONCAT('archivos/produccionDocumental/',cdpc.codoplsigla,'/',cdpc.codoplanio,'/', cdpa.codopxrutaanexo) as rutaDescargar"))	
						  ->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpa.codoprid')
						  ->join('coddocumprocesocircular as cdpc', 'cdpc.codoprid', '=', 'cdpa.codoprid')
						  ->where('cdpa.codoprid', $infodocumento->codoprid)->get();

		 return array ($infodocumento, $firmas, $copiaDependencias, $anexosDocumento);
    }

	function citacion($id)
	{
        $infodocumento =  DB::table('coddocumprocesocitacion as cdpc')
						->select('cdpc.codoptid as id','cdpc.tipactid', DB::raw("CONCAT(cdpc.codoptanio,' - ', cdpc.codoptconsecutivo) as consecutivogenerado"),
						'cdpc.codoptconsecutivo','cdpc.codoptsigla','cdpc.codoptanio','cdpc.codopthora','cdpc.codoptlugar',
						'cdpc.codoptfecharealizacion','cdp.tiesdoid','cdp.codoprid','cdp.codoprfecha','ta.tipactnombre',
						'cdp.codoprnombredirigido','cdp.codoprcargonombredirigido','cdp.codoprasunto','cdp.codoprcorreo','cdp.codoprcontenido','cdp.codoprtieneanexo',
						'cdp.codoprtienecopia','cdp.codoprsolicitafirma','cdp.codopranexonombre','cdp.codoprcopianombre', 'cdp.codoprfirmado','cdp.coddocid',
						DB::raw("if(cdp.codoprtieneanexo = 1 ,'Sí', 'No') as tieneanexo"),
						DB::raw("if(cdp.codoprtienecopia = 1 ,'Sí', 'No') as tienecopia"),
						'ted.tiesdonombre as estado','cd.depeid','cd.serdocid','cd.susedoid','cd.tipdocid','cd.tipmedid','cd.tiptraid','cd.tipdetid',
						'tdc.tipdoccodigo','sd.serdoccodigo', 'ssd.susedocodigo','d.depenombre as dependencia', 'd.depecodigo', 'u.usuaalias as alias',
						DB::raw('(SELECT COUNT(codopxid) AS codopxid FROM coddocumprocesoanexo WHERE codoprid = cdp.codoprid) AS totalAnexos'))
						->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
	  					->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
						->join('tipoestadodocumento as ted', 'ted.tiesdoid', '=', 'cdp.tiesdoid')
						->join('tipoacta as ta', 'ta.tipactid', '=', 'cdpc.tipactid')
						->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
						->join('seriedocumental as sd', 'sd.serdocid', '=', 'cd.serdocid')
						->join('subseriedocumental as ssd', function($join)
							{
								$join->on('ssd.susedoid', '=', 'cd.susedoid');
								$join->on('ssd.serdocid', '=', 'cd.serdocid'); 
							})
						->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
						->join('usuario as u', 'u.usuaid', '=', 'cd.usuaid')
						->where('cdpc.codoptid', $id)->first();

		$firmas     = $this->obtenerFirmaDocumento($infodocumento->codoprid);	
		$invitados  = $this->obtenerFirmaDocumento($infodocumento->codoprid, true);	
	
		return array ($infodocumento, $firmas, $invitados);
    }

	function constancia($id)
	{	
		$infodocumento =  DB::table('coddocumprocesoconstancia as cdpc')
						->select('cdpc.codopnid as id','cdpc.tipedoid', DB::raw("CONCAT(cdpc.codopnanio,' - ', cdpc.codopnconsecutivo) as consecutivogenerado"),
						'cdpc.codopnconsecutivo','cdpc.codopnsigla','cdpc.codopnanio', 'cdpc.codopntitulo','cdpc.codopncontenidoinicial','cdp.tiesdoid',
						'tpd.tipedonombre','cdp.codoprid','cdp.codoprfecha','cdp.codoprnombredirigido','cdp.codoprcorreo',	'cdp.codoprcontenido',
						'cdp.codoprtieneanexo','cdp.codoprtienecopia','cdp.codoprsolicitafirma', 'cdp.codoprfirmado','cdp.coddocid',
						DB::raw("if(cdp.codoprtieneanexo = 1 ,'Sí', 'No') as tieneanexo"),
						DB::raw("if(cdp.codoprtienecopia = 1 ,'Sí', 'No') as tienecopia"),
						'ted.tiesdonombre as estado',
						'cd.depeid','cd.serdocid','cd.susedoid','cd.tipdocid','cd.tipmedid','cd.tiptraid','cd.tipdetid',
						'tdc.tipdoccodigo','sd.serdoccodigo', 'ssd.susedocodigo',
						'd.depenombre as dependencia', 'd.depecodigo', 'u.usuaalias as alias',
						DB::raw('(SELECT COUNT(codopxid) AS codopxid FROM coddocumprocesoanexo WHERE codoprid = cdp.codoprid) AS totalAnexos'))
						->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
	  					->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
						->join('tipoestadodocumento as ted', 'ted.tiesdoid', '=', 'cdp.tiesdoid')
						->join('tipopersonadocumental as tpd', 'tpd.tipedoid', '=', 'cdpc.tipedoid')
						->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
						->join('seriedocumental as sd', 'sd.serdocid', '=', 'cd.serdocid')
						->join('subseriedocumental as ssd', function($join)
							{
								$join->on('ssd.susedoid', '=', 'cd.susedoid');
								$join->on('ssd.serdocid', '=', 'cd.serdocid'); 
							})
						->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
						->join('usuario as u', 'u.usuaid', '=', 'cd.usuaid')
						->where('cdpc.codopnid', $id)->first();

			$firmas = $this->obtenerFirmaDocumento($infodocumento->codoprid);

		return array ($infodocumento, $firmas);
	}
	
    function oficio($id)
	{
        $infodocumento =  DB::table('coddocumprocesooficio as cdpo')
						->select('cdpo.codopoid as id','cdpo.tipdesid','cdpo.tipsalid', DB::raw("CONCAT(cdpo.codopoanio,' - ', cdpo.codopoconsecutivo) as consecutivogenerado"),
						'cdpo.codopoconsecutivo','cdpo.codoposigla','cdpo.codopoanio', 'cdpo.codopotitulo','cdpo.codopociudad','cdpo.codopocargodestinatario',
						'cdpo.codopoempresa','cdpo.codopodireccion','cdpo.codopotelefono','cdpo.codoporesponderadicado','cdp.tiesdoid',
						DB::raw("if(cdpo.codoporesponderadicado = 1 ,'Sí', 'No') as responderadicado"), 'td.tipdesnombre','ts.tipsalnombre',
						'cdp.codoprid','cdp.codoprfecha','cdp.codoprnombredirigido','cdp.codoprcargonombredirigido','cdp.codoprasunto','cdp.codoprcorreo',
						'cdp.codoprcontenido','cdp.codoprtieneanexo','cdp.codoprtienecopia','cdp.codoprsolicitafirma',
						'cdp.codopranexonombre','cdp.codoprcopianombre', 'cdp.codoprfirmado','cdp.coddocid',
						DB::raw("if(cdp.codoprtieneanexo = 1 ,'Sí', 'No') as tieneanexo"),
						DB::raw("if(cdp.codoprtienecopia = 1 ,'Sí', 'No') as tienecopia"),
						'ted.tiesdonombre as estado',
						'cd.depeid','cd.serdocid','cd.susedoid','cd.tipdocid','cd.tipmedid','cd.tiptraid','cd.tipdetid',
						'tdc.tipdoccodigo','sd.serdoccodigo', 'ssd.susedocodigo',
						'd.depenombre as dependencia', 'd.depecodigo', 'u.usuaalias as alias',
						DB::raw('(SELECT COUNT(codopxid) AS codopxid FROM coddocumprocesoanexo WHERE codoprid = cdp.codoprid) AS totalAnexos'))
						->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpo.codoprid')
	  					->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
						->join('tipoestadodocumento as ted', 'ted.tiesdoid', '=', 'cdp.tiesdoid')
						->join('tipodespedida as td', 'td.tipdesid', '=', 'cdpo.tipdesid')
						->join('tiposaludo as ts', 'ts.tipsalid', '=', 'cdpo.tipsalid')
						->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
						->join('seriedocumental as sd', 'sd.serdocid', '=', 'cd.serdocid')
						->join('subseriedocumental as ssd', function($join)
							{
								$join->on('ssd.susedoid', '=', 'cd.susedoid');
								$join->on('ssd.serdocid', '=', 'cd.serdocid'); 
							})
						->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
						->join('usuario as u', 'u.usuaid', '=', 'cd.usuaid')
						->where('cdpo.codopoid', $id)->first();

		$firmas            = $this->obtenerFirmaDocumento($infodocumento->codoprid);
		$copiaDependencias = $this->obtenerCopiaDocumento($infodocumento->codoprid);

		$anexosDocumento = DB::table('coddocumprocesoanexo as cdpa')
						->select('cdpa.codopxid as id','cdpa.codopxnombreanexooriginal as nombreOriginal','cdpa.codopxnombreanexoeditado as nombreEditado',
						'cdpa.codopxrutaanexo as rutaAnexo', 'cdpo.codoposigla as sigla','cdpo.codopoanio as anio',DB::raw("CONCAT('1') as idFolder"),
						  'cdpo.codoposigla','cdpo.codopoanio',DB::raw("CONCAT('1') as idFolder"),
						  	DB::raw("CONCAT('archivos/produccionDocumental/',cdpo.codoposigla,'/',cdpo.codopoanio,'/', cdpa.codopxrutaanexo) as rutaDescargar"))	
						  ->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpa.codoprid')
						  ->join('coddocumprocesooficio as cdpo', 'cdpo.codoprid', '=', 'cdpa.codoprid')
						  ->where('cdpa.codoprid', $infodocumento->codoprid)->get();

		 return array ($infodocumento, $firmas, $copiaDependencias, $anexosDocumento);
    }

	function obtenerFirmaDocumento($id, $esInvitado = false){
		return  DB::table('coddocumprocesofirma as cdpf')
							->select('cdpf.codopfid', 'cdpf.persid', 'cdpf.carlabid',
							DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"),
							'p.persrutafirma','cl.carlabnombre','cdpf.codopffirmado', DB::raw("CONCAT('/archivos/persona/',p.persdocumento,'/',p.persrutafirma ) as firmaPersona"))
							->join('persona as p', 'p.persid', '=', 'cdpf.persid')
							->join('cargolaboral as cl', 'cl.carlabid', '=', 'cdpf.carlabid')
							->where('cdpf.codopfesinvitado', $esInvitado)
							->where('cdpf.codoprid', $id)->get();
	}

	function obtenerCopiaDocumento($id){
		return  DB::table('coddocumprocesocopia as cdpp')
						  ->select('d.depeid','d.depenombre','cdpp.codoppid')
						  ->join('dependencia as d', 'd.depeid', '=', 'cdpp.depeid')
						  ->where('cdpp.codoppescopiadocumento', true)
						  ->where('cdpp.codoprid', $id)->get();
	}
}