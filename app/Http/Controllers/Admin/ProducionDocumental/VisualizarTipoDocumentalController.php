<?php

namespace App\Http\Controllers\Admin\ProducionDocumental;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use Auth, DB;

class VisualizarTipoDocumentalController extends Controller
{
    public function index(Request $request)
	{
        $id              = $request->codigo;
		$tipo            = $request->tipoDocumental;
		$data            = [];
        $generarPdf    = new generarPdf();
		if($tipo === 'OFICIO'){			
            $dataDocumento =  $generarPdf->oficio( $id, 'S');
		}

        return response()->json(["exito" => true, "data" => $dataDocumento]);

    }
}