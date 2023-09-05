<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth, DB, URL;

class FrondController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',['only' => 'index']);
    }

    public function index() {
        return view('home.welcome');
    }
	
}