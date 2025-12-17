<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Mesa;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['mesa', 'mesero', 'cliente'])
            ->orderBy('fecha_pedido', 'desc')
            ->paginate(15);
        
        $stats = [
            'total' => Pedido::count(),
            'pendientes' => Pedido::pendiente()->count(),
            'en_preparacion' => Pedido::enPreparacion()->count(),
            'completados' => Pedido::where('estado', 'entregado')->count(),
        ];
        
        return view('pedidos.index', compact('pedidos', 'stats'));
    }

    public function create()
    {
        $mesas = Mesa::disponible()->orderBy('numero_mesa')->get();
        $productos = Producto::disponible()->with('categoria')->get();
        $meseros = Usuario::whereHas('role', function($q) {
            $q->where('nombre_rol', 'Mesero');
        })->get();
        
        return view('pedidos.create', compact('mesas', 'productos', 'meseros'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mesa' => 'required|exists:mesas,id_mesa',
            'id_cliente' => 'nullable|exists:usuarios,id_usuario',
            'observaciones' => 'nullable|string',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.observaciones' => 'nullable|string',
        ]);

        // Calcular total
        $total = 0;
        $productosData = [];
        
        foreach ($validated['productos'] as $productoData) {
            $producto = Producto::find($productoData['id']);
            $subtotal = $producto->precio * $productoData['cantidad'];
            $total += $subtotal;
            
            $productosData[] = [
                'id_producto' => $producto->id_producto,
                'cantidad' => $productoData['cantidad'],
                'precio_unitario' => $producto->precio,
                'subtotal' => $subtotal,
                'observaciones' => $productoData['observaciones'] ?? null,
            ];
        }

        // Crear pedido
        $pedido = Pedido::create([
            'id_mesa' => $validated['id_mesa'],
            'id_mesero' => auth()->id(), // El mesero autenticado
            'id_cliente' => $validated['id_cliente'],
            'observaciones' => $validated['observaciones'],
            'estado' => 'pendiente',
            'fecha_pedido' => now(),
            'total' => $total,
        ]);

        // Crear detalles del pedido
        foreach ($productosData as $detalle) {
            $pedido->detalles()->create($detalle);
        }

        // Cambiar estado de la mesa a ocupada
        $mesa = Mesa::find($validated['id_mesa']);
        $mesa->estado = 'ocupada';
        $mesa->save();

        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido creado exitosamente.');
    }

    public function show(Pedido $pedido)
    {
        $pedido->load(['mesa', 'mesero', 'cliente', 'pago', 'detalles.producto']);
        return view('pedidos.show', compact('pedido'));
    }

    public function edit(Pedido $pedido)
    {
        $mesas = Mesa::orderBy('numero_mesa')->get();
        return view('pedidos.edit', compact('pedido', 'mesas'));
    }

    public function update(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,en_preparacion,listo,entregado,cancelado',
            'observaciones' => 'nullable|string',
        ]);

        $pedido->update($validated);

        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido actualizado exitosamente.');
    }

    public function destroy(Pedido $pedido)
    {
        $pedido->delete();
        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido eliminado exitosamente.');
    }
}
