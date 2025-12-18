<?php

namespace App\Http\Controllers;

use App\Models\ContadorPagina;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard
     */
    public function index()
    {
        $usuario = auth()->user();
        $rol = $usuario->role->nombre_rol;

        if (strtolower($rol) === 'cliente') {
            return redirect()->route('cliente.dashboard');
        }

        // Obtener menú dinámico según el rol y convertirlo al formato esperado por el sidebar
        $menuItemsDB = $usuario->role->menuItems()
            ->activo()
            ->principal()
            ->orderBy('orden')
            ->get();

        // Mapeo de rutas a nombres de ruta
        $routeMap = [
            '/dashboard' => 'dashboard',
            '/pedidos' => 'pedidos.index',
            '/mesas' => 'mesas.index',
            '/menu' => 'menu.index',
            '/pagos' => 'pagos.index',
            '/facturas' => 'facturas.index',
            '/cajas' => 'cajas.index',
            '/cocina/pedidos' => 'cocina.pedidos',
            '/productos' => 'productos.index',
            '/mis-pedidos' => 'mis-pedidos',
            '/reservas' => 'reservas.index',
        ];

        // Convertir a formato esperado por el sidebar existente
        $menuItems = $menuItemsDB->map(function($item) use ($routeMap) {
            $routeName = $routeMap[$item->ruta] ?? 'dashboard';
            return [
                'label' => $item->nombre,
                'route' => $routeName,
                'icon' => 'bi ' . str_replace('fa-', 'bi-', $item->icono),
                'children' => []
            ];
        })->toArray();

        // Estadísticas generales
        $stats = [
            'total_productos' => Producto::count(),
            'productos_disponibles' => Producto::disponible()->count(),
            'total_pedidos' => Pedido::count(),
            'pedidos_pendientes' => Pedido::pendiente()->count(),
            'total_usuarios' => Usuario::where('estado', true)->count(),
        ];

        // Obtener visitas de las páginas principales
        $visitas = ContadorPagina::orderBy('total_visitas', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('usuario', 'rol', 'menuItems', 'stats', 'visitas'));
    }
}