<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

use App\Helpers\IL3Helper as il3;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
   public function index(){
    return Inertia::render('login/Login');
   }

   public function store(Request $request){
     
   $user =il3::registros("SELECT * FROM EUK_DAT_USUARIO WHERE USUARIO='$request->usuario'");
   if($user){
         return to_route('home');
   } 
 
   return redirect()->back()->with('message', 'Usuario y/o contraseña incorrectos');


   }
}