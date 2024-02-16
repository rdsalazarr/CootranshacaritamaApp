<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\ProducionDocumental\TokenFirmaPersona;
use App\Models\Vehiculos\VehiculoContratoFirma;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Models\Vehiculos\Vehiculo;
use Exception, Auth, DB, URL;
use App\Util\GenerarContrato;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\notificar;
use App\Util\generales;
use Carbon\Carbon;

class FirmarContratoController extends Controller
{
    public function index(Request $request)
    {
        $this->validate(request(),['tipo' => 'required']);

        try{

            $data = DB::table('vehiculocontratofirma as vcf')
                            ->select('v.vehiid','vcf.vecofiid', 'vc.vehconfechainicial as fechaContrato', DB::raw("CONCAT(vc.vehconanio, vc.vehconnumero) as numeroContrato"),
                            DB::raw("CONCAT(tv.tipvehnombre,' DE PLACA (',v.vehiplaca,') NÚMERO INTERNO (',v.vehinumerointerno,')') as nombreVehiculo"),
                            DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))
                            ->join('vehiculocontrato as vc', 'vcf.vehconid', '=', 'vc.vehconid')
                            ->join('vehiculo as v', 'v.vehiid', '=', 'vc.vehiid')
                            ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                            ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                            ->join('persona as p', 'p.persid', '=', 'a.persid')
                            ->where('vcf.persid', Auth::id())
                            ->orderBy('vcf.vecofiid')->get();

            return response()->json(['success' => true, "data" => $data]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }
}
