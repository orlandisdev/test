<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use App\Helpers\UtilHelper as util;

class HomeController extends Controller
{

    /**
     * 
     */
    public function home( Request $request ){
        if ($request ->has('lang')){
            $locale = $request ->get('lang');
            Session::put('locale',$locale);
            return redirect() ->route('home');
        }
        if (Auth::check()){
            return redirect() ->route('escritorio');
        }
        return view('escritorio');
    }
    
    /**
     * 
     */
    public function escritorio(){
        return view('escritorio');
    }
   
}
