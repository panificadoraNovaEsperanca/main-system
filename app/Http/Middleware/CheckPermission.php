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
        
        if($user == null){
            if($request->getRequestUri() === '/painel/login'){
                return $next($request);
            }
            abort(401,'Login Expirado!');
        }
        
        // Limpar cache se necessário e obter grupo
        $grupoUsuario = strtolower($user->obtemTodosGrupos());
        
        // Verificar se é administrador ou root (com diferentes variações possíveis)
        $isAdmin = in_array($grupoUsuario, ['administrador', 'admin', 'admnistrador', 'root']);
        if($isAdmin){
            return $next($request);
        }
        
        // Verificar se o usuário pertence a algum dos grupos permitidos
        foreach ($grupos as $grupo) {
            /** @var User $user */
            $bool = $user->pertenceAoGrupo(strtolower($grupo));
            if($bool) break;
        }
        
        abort_unless($bool, Response::HTTP_FORBIDDEN, 'Você não tem permissão para acessar esta página!');

        return $next($request);
    }
}
