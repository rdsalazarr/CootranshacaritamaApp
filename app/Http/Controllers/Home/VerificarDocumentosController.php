<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Util\encrypt;
use DB, URL;

class VerificarDocumentosController extends Controller
{
    public function documental($id) { 
        $encrypt  = new encrypt();  
        $codoprid = $encrypt->decrypted(urldecode($id));
        if($codoprid === ''){ return redirect('/error/url'); }
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
        if($codigodocumental->tipdoccodigo === 'O'){//Oficios
            $data =  DB::table('coddocumprocesooficio as cdpo')
							->select( DB::raw("CONCAT(tdc.tipdoccodigo,'-',d.depesigla,'-', cdpo.codopoconsecutivo) as consecutivoDocumento"),
										'tdc.tipdocnombre as tipoDocementa','cdp.codoprfecha as fechaDocumento',
                                        'cdp.codoprasunto as asunto','cdp.codoprnombredirigido as nombredirigido', 
								        'd.depenombre as dependenciaProductora',)
							->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpo.codoprid')
							->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
                            ->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
							->join('tipodocumental as tdc', 'tdc.tipdocid', '=', 'cd.tipdocid')
							->where('cdpo.codopoid', $codoprid)->first();
           
        }

        return view('home.verificar', ['dataDocumento' => $data] );
    }

}