<?php

namespace App\Http\Middleware;

use App\Models\ContadorPagina;
use App\Models\VisitaPagina;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo contar visitas para peticiones GET exitosas
        if ($request->isMethod('GET') && $response->getStatusCode() === 200) {
            
            // NORMALIZACIÓN DE URL PARA ESTADÍSTICAS
            $path = $request->path();
            
            // 1. Quitar IDs numéricos (ej: productos/5/edit -> productos/edit)
            // Reemplaza segmentos numéricos por vacio o placeholder si se desea, 
            // pero el usuario pidió "principales sin ramas".
            // Estrategia: Tomar solo el primer o primeros 2 segmentos si hay IDs.
            
            // Regex: Reemplazar cualquier segmento que sea solo números
            $normalized = preg_replace('#/\d+#', '', $path);
            
            // 2. Limpiar acciones comunes de CRUD al final (edit, create, etc) para agrupar en el recurso
            $normalized = preg_replace('#/(create|edit)$#', '', $normalized);

            // 3. Limpiar barras finales o dobles
            $normalized = trim($normalized, '/');
            
            // Caso especial: si quedó vacío (ej: home) o raíz
            if ($normalized === '') {
                $normalized = '/';
            }

            // Incrementar contador general de la página normalizada
            $contador = ContadorPagina::obtenerOCrear($normalized);
            $contador->incrementar();
            
            // Registrar visita detallada
            VisitaPagina::registrar(
                $normalized,
                auth()->check() ? auth()->id() : null
            );
        }

        return $response;
    }
}
