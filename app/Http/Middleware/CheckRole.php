<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión para acceder a esta página.');
        }

        $usuario = auth()->user();
        
        // Verificar si el usuario tiene un rol asignado
        if (!$usuario->role) {
            return redirect()->route('dashboard')
                ->with('error', 'No tiene un rol asignado.');
        }

        // Verificar si el rol del usuario está en los roles permitidos (case-insensitive)
        $userRole = strtolower($usuario->role->nombre_rol);
        $allowedRoles = array_map('strtolower', $roles);
        
        if (!in_array($userRole, $allowedRoles)) {
            return redirect()->route('dashboard')
                ->with('error', 'No tiene permisos para acceder a esta página.');
        }

        return $next($request);
    }
}
