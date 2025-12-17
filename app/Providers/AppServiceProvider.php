<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compartir menuItems con todas las vistas
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $menuItems = [];
            
            if (auth()->check() && auth()->user()->role) {
                // Obtener items de menú según el rol del usuario
                $roleId = auth()->user()->id_rol;
                
                $items = \App\Models\MenuItem::whereHas('roles', function($q) use ($roleId) {
                        $q->where('roles.id_rol', $roleId);
                    })
                    ->whereNull('parent_id')
                    ->where('activo', true)
                    ->orderBy('orden')
                    ->with(['children' => function($q) use ($roleId) {
                        $q->whereHas('roles', function($sq) use ($roleId) {
                            $sq->where('roles.id_rol', $roleId);
                        })
                        ->where('activo', true)
                        ->orderBy('orden');
                    }])
                    ->get();

                // Formatear para la vista
                foreach ($items as $item) {
                    // Determinar si es ruta nombrada o URL
                    $url = '#';
                    $isActive = false;
                    
                    if ($item->ruta) {
                        try {
                            if (str_starts_with($item->ruta, '/')) {
                                $url = url($item->ruta);
                                $isActive = request()->is(ltrim($item->ruta, '/'));
                            } else {
                                $url = route($item->ruta);
                                $isActive = request()->routeIs($item->ruta);
                            }
                        } catch (\Exception $e) {
                            $url = '#'; // Fallback por seguridad
                        }
                    }

                    $menuItem = [
                        'label' => $item->nombre,
                        'url' => $url, // Usamos URL resuelta en vez de nombre de ruta
                        'icon' => str_replace('fa-', 'bi bi-', $item->icono),
                        'active' => $isActive,
                        'children' => []
                    ];

                    foreach ($item->children as $child) {
                        $childUrl = '#';
                        $childActive = false;
                        
                        if ($child->ruta) {
                            try {
                                if (str_starts_with($child->ruta, '/')) {
                                    $childUrl = url($child->ruta);
                                    $childActive = request()->is(ltrim($child->ruta, '/'));
                                } else {
                                    $childUrl = route($child->ruta);
                                    $childActive = request()->routeIs($child->ruta);
                                }
                            } catch (\Exception $e) {
                                $childUrl = '#';
                            }
                        }

                        $menuItem['children'][] = [
                            'label' => $child->nombre,
                            'url' => $childUrl,
                            'active' => $childActive
                        ];
                    }

                    $menuItems[] = $menuItem;
                }
            }

            $view->with('menuItems', $menuItems);
        });
    }
}
