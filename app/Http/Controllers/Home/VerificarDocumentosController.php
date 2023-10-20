<?php

namespace App\Http\Controllers\Home;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\encrypt;
use App\Models\User;
use DB, URL;

class VerificarDocumentosController extends Controller
{
    public function documental($id) { 
        $encrypt  = new encrypt(); 
        $codoprid = $encrypt->decrypted(urldecode($id));
        if($codoprid === ''){ return redirect('/error/url'); }  

        return view('home.verificar', ['id' => $codoprid] );
    }

    public function consultarDocumento(Request $request)
	{
		$this->validate(request(),['id'=> 'required']);	
        $codoprid = $request->id;

        //Consulto el tipo documental
        /* 
            1 - Acta         A
            2 - Certificado  B
            3 - Circular     C
            4 - Citación     H
            5 - Constancia   T
            6 - Oficio       O 
        */

        $codigodocumental =  DB::table('codigodocumentalproceso as cdp')
							->select('cdp.codoprid','td.tipdocid','td.tipdoccodigo')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
                            ->join('tipodocumental as td', 'td.tipdocid', '=', 'cd.tipdocid')
							->where('cdp.codoprid', $codoprid)->first();
        $data = [];
        if($codigodocumental->tipdoccodigo === 'A'){//Actas
            $data =  DB::table('coddocumprocesoacta as cdpa')
							->select( DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpa.codopaconsecutivo) as consecutivoDocumento"),
										'tdc.tipdocnombre as tipoDocumento','cdp.codoprfecha as fechaDocumento', 'cdp.codoprrutadocumento as rutaDocumento',
                                        'cdp.codoprasunto as asunto','cdp.codoprnombredirigido as nombredirigido', 'cdp.tiesdoid as estado',
								        'd.depenombre as dependenciaProductora','d.depesigla as sigla', 'cdpa.codopaanio as anio')
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpa.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
                            ->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->where('cdpa.codopaid', $codoprid)->first();

        }else if($codigodocumental->tipdoccodigo === 'B'){//Certificado
            $data =  DB::table('coddocumprocesocertificado as cdpc')
							->select( DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpc.codopcconsecutivo) as consecutivoDocumento"),
										'tdc.tipdocnombre as tipoDocumento','cdp.codoprfecha as fechaDocumento', 'cdp.codoprrutadocumento as rutaDocumento',
                                        'cdp.codoprasunto as asunto','cdp.codoprnombredirigido as nombredirigido', 'cdp.tiesdoid as estado',
								        'd.depenombre as dependenciaProductora','d.depesigla as sigla', 'cdpc.codopcanio as anio')
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
                            ->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->where('cdpc.codopcid', $codoprid)->first();

        }else if($codigodocumental->tipdoccodigo === 'C'){//Circular
            $data =  DB::table('coddocumprocesocircular as cdpc')
							->select( DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpc.codoplconsecutivo) as consecutivoDocumento"),
										'tdc.tipdocnombre as tipoDocumento','cdp.codoprfecha as fechaDocumento', 'cdp.codoprrutadocumento as rutaDocumento',
                                        'cdp.codoprasunto as asunto','cdp.codoprnombredirigido as nombredirigido', 'cdp.tiesdoid as estado',
								        'd.depenombre as dependenciaProductora','d.depesigla as sigla', 'cdpc.codoplanio as anio')
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
                            ->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->where('cdpc.codoplid', $codoprid)->first();

        }else if($codigodocumental->tipdoccodigo === 'H'){//Citación
            $data =  DB::table('coddocumprocesocitacion as cdpc')
							->select( DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpc.codoptconsecutivo) as consecutivoDocumento"),
										'tdc.tipdocnombre as tipoDocumento','cdp.codoprfecha as fechaDocumento', 'cdp.codoprrutadocumento as rutaDocumento',
                                        'cdp.codoprasunto as asunto','cdp.codoprnombredirigido as nombredirigido', 'cdp.tiesdoid as estado',
								        'd.depenombre as dependenciaProductora','d.depesigla as sigla', 'cdpc.codoptanio as anio')
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
                            ->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->where('cdpc.codoptid', $codoprid)->first();

        }else if($codigodocumental->tipdoccodigo === 'T'){//Constancia
            $data =  DB::table('coddocumprocesoconstancia as cdpc')
							->select( DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpc.codopnconsecutivo) as consecutivoDocumento"),
										'tdc.tipdocnombre as tipoDocumento','cdp.codoprfecha as fechaDocumento', 'cdp.codoprrutadocumento as rutaDocumento',
                                        'cdp.codoprasunto as asunto','cdp.codoprnombredirigido as nombredirigido', 'cdp.tiesdoid as estado',
								        'd.depenombre as dependenciaProductora','d.depesigla as sigla', 'cdpc.codopnanio as anio')
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpc.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
                            ->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->where('cdpc.codopnid', $codoprid)->first();

        }else if($codigodocumental->tipdoccodigo === 'O'){//Oficios
            $data =  DB::table('coddocumprocesooficio as cdpo')
							->select( DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpo.codopoconsecutivo) as consecutivoDocumento"),
										'tdc.tipdocnombre as tipoDocumento','cdp.codoprfecha as fechaDocumento', 'cdp.codoprrutadocumento as rutaDocumento',
                                        'cdp.codoprasunto as asunto','cdp.codoprnombredirigido as nombredirigido', 'cdp.tiesdoid as estado',
								        'd.depenombre as dependenciaProductora','d.depesigla as sigla', 'cdpo.codopoanio as anio')
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpo.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
                            ->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->where('cdpo.codopoid', $codoprid)->first();

        }else{
            return response()->json(['success' => false,'data' => 'Documento no validado']);
        }
    
        return response()->json(['success' => true,'data' => $data]);
    }

    public function downloadDocumento($sigla, $anyo, $ruta){
        try {
            $ruta = Crypt::decrypt($ruta);
            $file = public_path().'/archivos/produccionDocumental/digitalizados/'.$sigla.'/'.$anyo.'/'.$ruta;
            if (file_exists($file)) {
                return response()->download($file, $ruta);
            } else {
                return redirect('/archivoNoEncontrado'.$ruta);
            }
        } catch (DecryptException $e) {
            return redirect('/error/url');
        }
    }
}