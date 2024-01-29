<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Http\Controllers\Controller;
use Exception, Auth, DB, URL;
use Illuminate\Http\Request;

class HistorialSolicitudCreditoController extends Controller
{
    public function index()
    {
        try{
            $data = DB::table('solicitudcredito as sc')
                            ->select('sc.solcreid', 'p.persid', 'sc.solcrefechasolicitud','sc.solcredescripcion','sc.solcrenumerocuota','tesc.tiesscnombre',
                            DB::raw("CONCAT('$ ', FORMAT(sc.solcrevalorsolicitado, 0)) as valorSolicitado"),'lc.lincrenombre as lineaCredito',
                            DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombrePersona"))
                            ->join('persona as p', 'p.persid', '=', 'sc.persid')
                            ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                            ->join('tipoestadosolicitudcredito as tesc', 'tesc.tiesscid', '=', 'sc.tiesscid')
                            ->orderBy('sc.solcreid')->get();

            return response()->json(['success' => true, "data" => $data]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }
}