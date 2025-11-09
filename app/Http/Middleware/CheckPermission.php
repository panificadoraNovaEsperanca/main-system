<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class CheckPermission
{

    public function handle(Request $request, Closure $next, string $permissao)
    {
        $grupos = explode('|', $permissao);
        $bool   = false;
        $user = Auth::user();
        $grupoUsuario = strtolower($user->obtemTodosGrupos());
        if($grupoUsuario == 'administrador' || $grupoUsuario == 'root'){
            return $next($request);
        }
        if($user != null){
            foreach ($grupos as $grupo) {
                /** @var User $user */
                $bool = $user->pertenceAoGrupo(strtolower($grupo));
                if($bool) break;
            }
            abort_unless($bool, Response::HTTP_FORBIDDEN, 'Você não tem permissão para acessar esta página!');

            return $next($request);
        }

        if($request->getRequestUri() === '/painel/login'){
            return $next($request);
        }
        abort(401,'Login Expirado!');

    }
}
