<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class CocinaController extends Controller
{
    /**
     * Vista de cocina con pedidos en Kanban
     */
    public function pedidos()
    {
        $pendientes = Pedido::with(['mesa', 'detalles.producto'])
            ->pendiente()
            ->orderBy('fecha_pedido')
            ->get();

        $enPreparacion = Pedido::with(['mesa', 'detalles.producto'])
            ->enPreparacion()
            ->orderBy('fecha_pedido')
            ->get();

        $listos = Pedido::with(['mesa', 'detalles.producto'])
            ->listo()
            ->orderBy('fecha_pedido')
            ->get();

        return view('cocina.pedidos', compact('pendientes', 'enPreparacion', 'listos'));
    }

    /**
     * Cambiar estado de pedido
     */
    public function cambiarEstado(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);
        $estadoAnterior = $pedido->estado;
        
        $request->validate([
            'estado' => 'required|in:pendiente,en_preparacion,listo'
        ]);

        // Lógica de Inventario: Descontar al iniciar preparación
        // Solo si pasa de pendiente a en_preparacion (para evitar doble descuento)
        if ($request->estado == 'en_preparacion' && $estadoAnterior == 'pendiente') {
            $pedido->load('detalles.producto.insumos');
            
            foreach ($pedido->detalles as $detalle) {
                // Si el producto tiene receta (insumos)
                if ($detalle->producto && $detalle->producto->insumos->count() > 0) {
                    foreach ($detalle->producto->insumos as $insumo) {
                        // Cantidad a descontar = (Lo que dicta la receta) * (Cantidad ordenadas)
                        $cantidadTotal = $insumo->pivot->cantidad_requerida * $detalle->cantidad;
                        
                        // Descontar del stock
                        $insumo->stock_actual -= $cantidadTotal;
                        $insumo->save();
                    }
                }
            }
        }

        $pedido->cambiarEstado($request->estado);

        return redirect()->route('cocina.pedidos')
            ->with('success', 'Estado del pedido actualizado e inventario ajustado.');
    }
}
