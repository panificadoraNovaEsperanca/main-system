<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        
        // Força HTTPS apenas em produção E quando APP_URL usar HTTPS
        // Não força HTTPS em localhost ou desenvolvimento
        $appUrl = config('app.url', 'http://localhost');
        $isProduction = config('app.env') === 'production';
        $isHttpsUrl = strpos($appUrl, 'https://') === 0;
        $isLocalhost = strpos($appUrl, 'localhost') !== false || strpos($appUrl, '127.0.0.1') !== false;
        
        if($isProduction && $isHttpsUrl && !$isLocalhost) {
            \URL::forceScheme('https');
        }


        Blade::if('hasGroup', function (string $groups) {
            $grupos = explode('|', $groups);
            $bool   = false;
            $user = Auth::user();
            if($user->grupoPermissao == null ){
                return true;
            }
            $grupoUsuario = strtolower($user->obtemTodosGrupos());
            if($grupoUsuario == 'administrador' || $grupoUsuario == 'root'){
                return true;
            }
            foreach ($grupos as $grupo) {
                $bool = $user->pertenceAoGrupo(strtolower($grupo));
            }

            return $bool;
        });
        Blade::if('hasPermission', function (string $groups) {
            $grupos = explode('|', $groups);
            $bool   = false;
            $user = Auth::user();
            $grupoUsuario = strtolower($user->obtemTodosGrupos());
            if($grupoUsuario == 'administrador' || $grupoUsuario == 'root'){
                return true;
            }
            foreach ($grupos as $grupo) {
                if($user->pertenceAPermissao(strtolower($grupo))){
                    return true;
                }
             
            }

            return $bool;
        });
    }
}
