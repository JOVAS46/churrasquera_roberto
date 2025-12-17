<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function query(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return redirect()->back();
        }

        // 1. Buscar en Módulos del Sistema (Navegación Rápida)
        $modulos = collect([
            ['nombre' => 'Ventas', 'ruta' => route('ventas.index'), 'icono' => 'bi-cash-coin'],
            ['nombre' => 'Insumos / Stock', 'ruta' => route('admin.insumos.index'), 'icono' => 'bi-box-seam'],
            ['nombre' => 'Reportes', 'ruta' => route('admin.reportes'), 'icono' => 'bi-graph-up'],
            ['nombre' => 'Pedidos', 'ruta' => route('pedidos.index'), 'icono' => 'bi-list-check'],
            ['nombre' => 'Mesas', 'ruta' => route('mesas.index'), 'icono' => 'bi-grid-3x3-gap'],
            ['nombre' => 'Usuarios', 'ruta' => route('admin.usuarios'), 'icono' => 'bi-people'],
        ])->filter(function ($item) use ($query) {
            return stripos($item['nombre'], $query) !== false;
        });

        // 2. Buscar en Pedidos (Por ID o Cliente)
        $pedidosQuery = \App\Models\Pedido::with(['cliente', 'mesa']);

        if (is_numeric($query)) {
            // Si es número, buscar por ID exacto
            $pedidosQuery->where('id_pedido', $query);
        } else {
            // Si es texto, buscar por Cliente
            $pedidosQuery->whereHas('cliente', function ($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                  ->orWhere('apellido', 'LIKE', "%{$query}%");
            });
        }
            
        $pedidos = $pedidosQuery->latest('fecha_pedido')
            ->take(5)
            ->get();

        // 3. Buscar en Productos
        $productos = Producto::where('nombre', 'LIKE', "%{$query}%")
            ->orWhere('descripcion', 'LIKE', "%{$query}%")
            ->get();

        // 4. Buscar en Categorías
        $categorias = Categoria::where('nombre', 'LIKE', "%{$query}%")
            ->orWhere('descripcion', 'LIKE', "%{$query}%")
            ->get();

        return view('search.results', compact('productos', 'categorias', 'modulos', 'pedidos', 'query'));
    }
}
