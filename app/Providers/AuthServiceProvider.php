<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Helpers\IL3Helper as il3;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this ->registerPolicies();
       /*
       foreach( il3::registros('select * from EUK_ order by idPermiso') as $permiso ){
            //dd($permiso );
            Gate::define( $permiso ->cdpermiso, function ($user) use ($permiso) {
                return $user ->hasPermiso( $permiso ->cdpermiso );
            });
        }
       
       */ 
    }
}
