<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

//use App\Ldap\User;
use App\Ldap\Usuario;

use Illuminate\Support\Facades\Auth;
use App\Helpers\IL3Helper as il3;
use Inertia\Inertia;

class LdapUserController extends Controller
{ 
    protected $user;
    
    public function index()
    {
 

        $credentials = [
            'username' => 'IL3\macarmona.eureka@il3.ub.edu',
            'password' => 'mac#at.##957aa1',
        ];
     
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            dd(['message' => "Welcome back, {$user}"]);
            //return redirect('/dashboard')->with([
            //    'message' => "Welcome back, {$user->name}"
            //]);
        }else{

            dd('No entra en el usuario');
        }


//        $users = Usuario::get();
  //      return view('ldap.users.index', ['users' => $users]);
    }
    public function view()
    {
       
        return Inertia::render('Welcome', ['user'=>$this->user]);
    }

    public function login (Request $request)
    {
        $registros =il3::registros("SELECT * FROM il3_usuario WHERE DSUSUARIO='$request->user'");
        if($registros){
            $this->user = $registros[0];
            return to_route('main.view');
        }
        return back()->with('error', 'El usuario o contraseña es incorrecto');  
    }
}
