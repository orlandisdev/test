<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Helpers\IL3Helper as il3;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table      = 'EUK_DAT_USUARIO';
    //protected $connection = 'oracle';
    protected $primaryKey = 'ID_USUARIO';
    public $timestamps    = false;
    public $incrementing  = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    /**
     * 
     */
    public function getIdAttribute(){
        return $this->idusuario;
    }
    public function getDsAttribute(){
        return $this->dsusuario;
    }
    public function getPermisosAttribute(){
        Cache::forget('permisos_' .$this->idUsuario);
        return Cache::remember('permisos_' .$this ->idUsuario, now()->addMinutes(30), function(){
            $regs = il3::registros( 'select rol.idRol, rol.dsRol, per.idPermiso, per.dsPermiso, per.cdPermiso
                    from il3_permiso per 
                    left join il3_rol_permiso rper on rper.idPermiso=per.idPermiso
                    left join il3_usuario_rol urol on urol.idRol=rper.idRol
                    left join il3_rol rol on rol.idRol=urol.idRol
                    where urol.idUsuario = ?
                    order by per.idPermiso
                ', [ Auth::user()->id ]
            );
            $permisos = [];
            if (!empty($regs)){
                foreach($regs as $reg){
                    $permisos[ $reg->cdpermiso ] = $reg;
                }
            }
            return $permisos;
        });
    }
    
    /**
     * 
     */
    public function hasPermiso( $permiso ){
        return isset($this->permisos[ $permiso ]);
    }
}
