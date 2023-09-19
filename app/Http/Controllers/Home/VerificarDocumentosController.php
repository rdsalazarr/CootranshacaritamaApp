<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB, URL;

class VerificarDocumentosController extends Controller
{
    public function documental(Request $request) {
        return view('home.welcome');
    }
	
}