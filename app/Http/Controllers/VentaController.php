<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    /**
     * Mostrar lita de ventas
     */
    public function index()
    {
        // Administradores y Gerentes ven todas, Cajeros solo las suyas (o todas, depende de regla negocio)
        // Por ahora todas para admin/gerente/cajero
        $ventas = Venta::with(['usuario', 'cliente', 'reserva'])->orderBy('fecha_venta', 'desc')->paginate(10);
        return view('ventas.index', compact('ventas'));
    }

    /**
     * Mostrar interfaz de nueva venta (POS)
     */
    public function create()
    {
        $productos = \App\Models\Producto::where('disponible', true)->get();
        $clientes = \App\Models\Usuario::whereHas('role', function($q) {
            $q->where('nombre_rol', 'cliente'); // Si existiera rol cliente
            // O simplemente listar usuarios generales
        })->get(); 
        
        // Cargar vista de creación
        return view('ventas.create', compact('productos'));
    }

    /**
     * Guardar nueva venta
     */
    public function store(Request $request)
    {
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $total = 0;
            $detalles = [];

            // Calcular total y preparar detalles
            foreach ($request->productos as $item) {
                $producto = \App\Models\Producto::find($item['id_producto']);
                $subtotal = $producto->precio * $item['cantidad'];
                $total += $subtotal;

                $detalles[] = [
                    'id_producto' => $producto->id_producto,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'subtotal' => $subtotal,
                ];
            }

            // Crear Venta
            $venta = Venta::create([
                'fecha_venta' => now(),
                'total' => $total,
                'estado' => 'pendiente', // Por defecto pendiente hasta confirmar pago
                'id_usuario' => auth()->id(), // Usuario autenticado (Cajero)
                // 'id_cliente' => $request->id_cliente, // Opcional
            ]);

            // Guardar Detalles
            foreach ($detalles as $detalle) {
                $venta->detalles()->create($detalle);
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('ventas.show', $venta->id_venta)
                             ->with('success', 'Venta registrada. Proceda al pago.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalle de una venta
     */
    public function show($id)
    {
        $venta = Venta::with(['detalles.producto', 'usuario', 'cliente'])->findOrFail($id);
        
        // Buscar si tiene pago asociado con QR
        // Asumiendo relación manual o consulta directa por ahora si no definimos relación en modelo Venta todavía
        $pago = \Illuminate\Support\Facades\DB::table('pagos')->where('id_venta', $id)->first();
        
        return view('ventas.show', compact('venta', 'pago'));
    }

    /**
     * Generar QR para una venta
     */
    public function generarQR($id)
    {
        $venta = Venta::findOrFail($id);
        $servicio = new \App\Services\PagoFacilService();

        $resultado = $servicio->generarQR($venta->id_venta, $venta->total);

        if ($resultado['success']) {
            // Guardar en tabla pagos
            \Illuminate\Support\Facades\DB::table('pagos')->updateOrInsert(
                ['id_venta' => $venta->id_venta],
                [
                    'monto' => $venta->total,
                    'fecha_pago' => now(),
                    'estado' => 'pendiente',
                    'id_metodo_pago' => 4, // Asumiendo ID 4 es QR segun seeder, o buscarlo
                    'qr_image' => $resultado['qr_image'],
                    'nro_transaccion' => $resultado['nro_transaccion'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return back()->with('success', 'QR Generado correctamente.');
        } else {
            return back()->with('error', 'Error al generar QR: ' . $resultado['message']);
        }
    }

    /**
     * Verificar estado del pago
     */
    public function verificarEstado($id)
    {
        $venta = Venta::findOrFail($id);
        $pago = \Illuminate\Support\Facades\DB::table('pagos')->where('id_venta', $id)->first();

        if (!$pago || !$pago->nro_transaccion) {
            return back()->with('error', 'No hay transacción de pago para verificar.');
        }

        $servicio = new \App\Services\PagoFacilService();
        // Usar tnTransaccionPF si es V2
        $resultado = $servicio->consultarEstado($pago->nro_transaccion);

        if ($resultado['success']) {
             // Simulación: Si el mensaje contiene "Exito" o "Procesado" o simplemente API responde OK
             // En un sistema real se validad "COMPLETADO"
             
            // ACTUALIZACIÓN: Marcar como completado
            $venta->estado = 'completada';
            $venta->save();
            
            \Illuminate\Support\Facades\DB::table('pagos')
                ->where('id_pago', $pago->id_pago)
                ->update(['estado' => 'completado']);

            return back()->with('success', 'Pago verificado y venta completada.');
        } else {
            return back()->with('error', 'No se pudo verificar el pago: ' . $resultado['message']);
        }
    }

    /**
     * Listar pedidos pendientes de cobro (Mesas ocupadas/entregadas)
     */
    public function pendientes()
    {
        // Pedidos que no tienen venta asociada y no están cancelados
        $pedidos = \App\Models\Pedido::doesntHave('venta')
            ->where('estado', '!=', 'cancelado')
            ->with(['mesa', 'mesero'])
            ->orderBy('fecha_pedido', 'desc')
            ->get();
            
        return view('ventas.pendientes', compact('pedidos'));
    }

    /**
     * Convertir un Pedido en una Venta
     */
    public function procesarPedido($idPedido)
    {
        $pedido = \App\Models\Pedido::with('detalles.producto')->findOrFail($idPedido);

        if ($pedido->venta) {
            return redirect()->route('ventas.show', $pedido->venta->id_venta)
                ->with('info', 'Este pedido ya tiene una venta asociada.');
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 1. Crear Venta
            $venta = Venta::create([
                'fecha_venta' => now(),
                'total' => $pedido->total,
                'estado' => 'pendiente',
                'id_usuario' => auth()->id(), // Cajero que procesa
                'id_cliente' => $pedido->id_cliente,
                'id_pedido' => $pedido->id_pedido,
            ]);

            // 2. Copiar Detalles
            foreach ($pedido->detalles as $detallePedido) {
                $venta->detalles()->create([
                    'id_producto' => $detallePedido->id_producto,
                    'cantidad' => $detallePedido->cantidad,
                    'precio_unitario' => $detallePedido->precio_unitario,
                    'subtotal' => $detallePedido->subtotal,
                ]);
            }
            
            // 3. (Opcional) Liberar mesa si se desea, o esperar a pago. 
            // Por ahora mantenemos ocupada hasta pago final.

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('ventas.show', $venta->id_venta)
                ->with('success', 'Pedido convertido en Venta. Proceda al cobro.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error al procesar pedido: ' . $e->getMessage());
        }
    }
}
