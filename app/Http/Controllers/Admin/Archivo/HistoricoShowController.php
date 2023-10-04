<?php

namespace App\Http\Controllers\Admin\Archivo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class HistoricoShowController extends Controller
{
    public function index(Request $request)
	{
	    $this->validate(request(),['codigo' => 'required']);
        $codigo        = $request->codigo;
 
        $data = DB::table('archivohistorico as ah')
                        ->select('ah.tipdocid', 'td.tipdocnombre', 'tea.tiesarnombre','tcu.ticaubnombre','ah.archisfechahora',
                        'ah.archisfechadocumento','ah.archisnumerofolio','ah.archisasuntodocumento','ah.archistomodocumento',
                        'ah.archiscodigodocumental','ah.archisentidadremitente','ah.archisentidadproductora','ah.archisresumendocumento',
                        'ah.archisobservacion', DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                        ->join('tipodocumental as td', 'td.tipdocid', '=', 'ah.tipdocid')
                        ->join('tipoestantearchivador as tea', 'tea.tiesarid', '=', 'ah.tiesarid')
                        ->join('tipocajaubicacion as tcu', 'tcu.ticaubid', '=', 'ah.ticaubid')
                        ->join('tipocarpetaubicacion as tcb', 'tcb.ticrubid', '=', 'ah.ticrubid')
                        ->leftJoin('usuario as u', 'u.usuaid', '=', 'ah.usuaid')
                        ->where('ah.archisid', $codigo)->first();
                        
        $digitalizados  =  DB::table('archivohistoricodigitalizado as ahd')
                                ->select('ahd.arhidiid as id','ahd.arhidinombrearchivooriginal as nombreOriginal','ahd.arhidinombrearchivoeditado as nombreEditado',
                                'ahd.arhidirutaarchivo as rutaArchivo', DB::raw("YEAR(ah.archisfechadocumento) as anio"),
                                DB::raw("CONCAT('archivos/digitalizados/',YEAR(ah.archisfechadocumento),'/', ahd.arhidirutaarchivo) as rutaDescargar"))
                                ->join('archivohistorico as ah', 'ah.archisid', '=', 'ahd.archisid')
                                ->where('ahd.archisid', $codigo)->get();

        return response()->json(["data" => $data, "digitalizados" => $digitalizados]);
    }
}