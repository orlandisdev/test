<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Helpers\UtilHelper as util;

class IL3Controller extends Controller
{

    /**
     * 
     */
    public function opcion(){
        return view('opciones.' . Route::currentRouteName());
    }
   
}