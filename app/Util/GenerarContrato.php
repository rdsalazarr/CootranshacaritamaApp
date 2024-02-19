<?php

namespace App\Util;

use App\Util\generarPdf;
use App\Util\generales;
use Exception, DB;
use Carbon\Carbon;

class GenerarContrato
{
    public static function vehiculo($contratoId, $metodo = 'S')
    {
        $vehiculoContrato = DB::table('vehiculocontrato as vc')
                        ->select('vc.vehconid','vc.vehconfechainicial','vc.vehconfechafinal', DB::raw("CONCAT(vc.vehconanio, vc.vehconnumero) as numeroContrato"),
                        'v.vehinumerointerno','v.vehiplaca','v.timoveid','tmv.timovecuotasostenimiento','tmv.timovedescuentopagoanticipado','tmv.timoverecargomora',
                        'p.persdocumento', 'p.persdireccion', 'p.perscorreoelectronico','p.persnumerocelular', 'p.perstienefirmaelectronica', 
                        'pe.persdocumento as documentoGerente','me.muninombre as nombreMunicipioExpedicion', 
                        DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"),                        
                        DB::raw("CONCAT(pe.persprimernombre,' ',IFNULL(pe.perssegundonombre,''),' ',pe.persprimerapellido,' ',IFNULL(pe.perssegundoapellido,'')) as nombreGerente"),
                        DB::raw("(SELECT COUNT(vcf1.vecofiid) FROM vehiculocontratofirma as vcf1 INNER JOIN vehiculocontrato as vc1 ON vc1.vehconid = vcf1.vehconid WHERE vcf1.vehconid = 'vc.vehconid') AS totalFirmas"),
                        DB::raw("(SELECT COUNT(vcf2.vecofiid) FROM vehiculocontratofirma as vcf2 INNER JOIN vehiculocontrato as vc ON vc.vehconid = vcf2.vehconid WHERE vcf2.vehconid = 'vc.vehconid' and vcf2.vecofifirmado = 1) AS totalFirmasRealizadas"))
                        ->join('vehiculo as v', 'v.vehiid', '=', 'vc.vehiid')
                        ->join('tipomodalidadvehiculo as tmv', 'tmv.timoveid', '=', 'v.timoveid')
                        ->join('asociado as a', 'a.asocid', '=', 'vc.asocid')
                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                        ->join('persona as pe', 'pe.persid', '=', 'vc.persidgerente')
                        ->join('empresa as e', 'e.persidrepresentantelegal', '=', 'pe.persid')
                        ->join('municipio as me', function($join)
                        {
                            $join->on('me.munidepaid', '=', 'p.persdepaidexpedicion');
                            $join->on('me.muniid', '=', 'p.persmuniidexpedicion'); 
                        })
                        ->where('vc.vehconid', $contratoId)
                        ->first();

        $empresa =  DB::table('empresa as e')->select('e.emprcorreo','p.persdocumento',
                        DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreGerente"))
                        ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                        ->where('emprid', '1')->first();

        if( $vehiculoContrato->perstienefirmaelectronica and $vehiculoContrato->totalFirmas === $vehiculoContrato->totalFirmasRealizadas){
            $firmasContrato = DB::table('vehiculocontratofirma as vcf')
                                ->select('vcf.vecofitoken','vcf.vecofifechahorafirmado', DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombrePersona"))
                                ->join('persona as p', 'p.persid', '=', 'vcf.persid')
                                ->where('vc.vehconid', $contratoId)
                                ->get();

            foreach($firmasContrato as $firmaContrato){
                $tokeFirma     = $firmaContrato->vecofitoken;
                $fechaFirmado  = $firmaContrato->vecofifechahorafirmado;
                $nombrePersona = $firmaContrato->nombrePersona;

            }
        }

        if($vehiculoContrato->timoveid === 'E' ){
            $idInformacionPdf = 'contratoModalidadEspecial';
        }else  if($vehiculoContrato->timoveid === 'I'){
            $idInformacionPdf = 'contratoModalidadIntermunicipal';
        }else  if($vehiculoContrato->timoveid === 'C'){
            $idInformacionPdf = 'contratoModalidadColectivo';
        } else {
            $idInformacionPdf = 'contratoModalidadMixto';
        }

        $generales                      = new generales();  
        $generarPdf                     = new generarPdf();
        $informacionPDF                 = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', $idInformacionPdf)->first();
        $fechaFirmaContrato             = $generales->formatearFecha($vehiculoContrato->vehconfechainicial);
        $cuotaSostenimientoAdmon        = number_format($vehiculoContrato->timovecuotasostenimiento, 0, ',', '.') ;
        $descuentoPagoAnualAnticipado   = $vehiculoContrato->timovedescuentopagoanticipado;
        $recargoCuotaSostenimientoAdmon = $vehiculoContrato->timoverecargomora;
        $nombreGerente                  = $empresa->nombreGerente;
        $documentoGerente               = number_format($empresa->persdocumento, 0, ',', '.');
        $ciudadExpDocumentoGerente      = $vehiculoContrato->nombreMunicipioExpedicion;;
        $numeroContrato                 = $vehiculoContrato->numeroContrato;
        $fechaContrato                  = $generales->formatearFechaContrato($vehiculoContrato->vehconfechainicial);
        $tipoContrato                   = $vehiculoContrato->timoveid;

        $identificacionAsociado         = '';
        $nombreAsociado                 = '';
        $direccionAsociado              = '';
        $telefonoAsociado               = '';
        $correoAsociado                 = '';
        $nombreGerenteFirma             = $nombreGerente;
        $documentoGerenteFirma          = 'C.C. '.$documentoGerente;
        $arrayFirmas                    = [];
    
        $identificacionAsociado .= number_format($vehiculoContrato->persdocumento, 0, ',', '.').', ';
        $nombreAsociado         .= trim($vehiculoContrato->nombreAsociado).', ';
        $direccionAsociado      .= $vehiculoContrato->persdireccion.', ';
        $telefonoAsociado       .= ($vehiculoContrato->persnumerocelular !== null ) ? $vehiculoContrato->persnumerocelular.', ': '';
        $correoAsociado         .= ($vehiculoContrato->perscorreoelectronico !== null ) ? $vehiculoContrato->perscorreoelectronico.', ': ''; 

        $firmasContrato = [
                "nombreGerente"     => $nombreGerenteFirma,
                "nombreAsociado"    => $vehiculoContrato->nombreAsociado,
                "documentoGerente"  => $documentoGerenteFirma,
                "documentoAsociado" => 'C.C. '.number_format($vehiculoContrato->persdocumento, 0, ',', '.'),
                "direccionAsociado" => $vehiculoContrato->persdireccion
            ];

            /*Documento firmado electrónicamente el día fechaFirmado, mediante el token númeroToken por nombrePersona*/

        array_push($arrayFirmas, $firmasContrato); 

        $arrayDatos = [ "titulo"                 => 'Contrato número '.$numeroContrato,
                        "numeroContrato"         => $numeroContrato,
                        "placaVehiculo"          => $vehiculoContrato->vehiplaca,
                        "numeroInterno"          => $vehiculoContrato->vehinumerointerno,
                        "propietarios"           => substr($nombreAsociado, 0, -2),
                        "identificaciones"       => substr($identificacionAsociado, 0, -2),
                        "direcciones"            => substr($direccionAsociado, 0, -2),
                        "telefonos"              => substr($telefonoAsociado, 0, -2),
                        "correos"                => substr($correoAsociado, 0, -2),
                        "firmadoElectonicamente" => ($vehiculoContrato->totalFirmas === $vehiculoContrato->totalFirmasRealizadas) ? true : false,
                        "metodo"                 => $metodo
                    ];                       

        $buscar      = Array('documentoGerente', 'nombreGerente', 'ciudadExpDocumentoGerente', 'cuotaSostenimientoAdmon','descuentoPagoAnualAnticipado',
                            'recargoCuotaSostenimientoAdmon','fechaFirmaContrato','fechaContrato');
        $remplazo    = Array($documentoGerente, $nombreGerente, $ciudadExpDocumentoGerente, $cuotaSostenimientoAdmon, $descuentoPagoAnualAnticipado,
                            $recargoCuotaSostenimientoAdmon, $fechaFirmaContrato, $fechaContrato); 
        $contenido   = str_replace($buscar,$remplazo,$informacionPDF->ingpdfcontenido);
        $pdfGenerado = $generarPdf->contratoVehiculo($arrayDatos, $contenido, $arrayFirmas, $tipoContrato);

        return $pdfGenerado;
    }
}