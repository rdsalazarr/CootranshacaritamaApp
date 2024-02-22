<?php

namespace App\Http\Controllers\Admin\Informes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class InformeDescargableController extends Controller
{
    public function index()
    {
        try{
            $agencias = DB::table('agencia')->select('agenid','agennombre')->where('agenactiva', true)->orderBy('agennombre')->get();

            return response()->json(['success' => true, "agencias" => $agencias]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }
}