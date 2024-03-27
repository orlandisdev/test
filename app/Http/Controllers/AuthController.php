<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log; 
use App\Helpers\UtilHelper as util;
use App\Helpers\IL3Helper as il3;
use App\Models\User;

class AuthController extends Controller
{
    
    protected $username;
    
    public function __construct()
    {
        if (!empty(config('cas.cas_hostname'))){
            $this ->middleware('cas.auth');
            cas() ->authenticate();
            $this ->username = cas()->user();
        }
    }
    
    /**
     * En producción siempre por CAS, se deja el formulario para simular un acceso sin CAS en desarrollo
     */
    public function login(){
        if (Auth::check()){
            return redirect() ->route('escritorio');
        } elseif (!empty($this ->username)){
            return self::loginEntrarAux( $this ->username );
        } else {
            return view('auth.login');
        }
    }
   
    /**
     */
    public function loginEntrar(Request $request){
        return self::loginEntrarAux( (!empty($request) ? $request ->input('username') : $this ->username ) );
    }
    public static function loginEntrarAux( $username ){
        //$usuario = il3::registro('select * from il3_usuario where dsUsuario=?', ['prueba']);
        $usuario = User::where('dsUsuario', $username) ->first();
        if (empty($usuario)){
            \Session::flash(
               'flash-danger',
               'Acceso incorrecto'
            );
            return redirect() ->route('login');
        }
        Auth::login($usuario);
        Log::debug('login '. $username . ' ok');
        return redirect() ->intended(route('home'));
    }

    /**
     * 
     */
    public function logout(){
        Log::debug('logout '. Auth::user() ->username . ' ok');
        Auth::logout();
        return redirect() ->route('home');
    }
    
    /**
     * 
     */
    public function permisos(){
        //util::Ver(Auth::user() ->permisos);
        return response([
            'status'   => 'success',
            'permisos' => array_values(Auth::user() ->permisos),
            'code'     => 200,
        ]);
    }
    
    
}
