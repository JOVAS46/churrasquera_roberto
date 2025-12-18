<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Pedido;
use App\Models\Mesa;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClienteController extends Controller
{
    // Helper to get menu items from DB (same logic as DashboardController)
    private function getMenuItems()
    {
        $usuario = auth()->user();
        $menuItemsDB = $usuario->role->menuItems()
            ->activo()
            ->principal()
            ->orderBy('orden')
            ->get();

        // Adapters to match sidebar format
        return $menuItemsDB->map(function($item) {
             $hasChildren = $item->children->count() > 0;
             $url = $hasChildren ? '#' : url($item->ruta);
             // Ensure font-awesome classes are mapped to bootstrap-icons if needed
             // Sidebar blade handles 'bi ' + icon. 
             // Seeder uses 'fa-home'. Let's replace 'fa-' with 'bi-' generically or keep as is if sidebar handles it.
             // Looking at DashboardController it does: 'icon' => 'bi ' . str_replace('fa-', 'bi-', $item->icono),
             $icon = 'bi ' . str_replace('fa-', 'bi-', $item->icono);

             $mappedChildren = $item->children->map(function($child) {
                 return [
                     'label' => $child->nombre,
                     'url' => url($child->ruta),
                     'active' => request()->fullUrlIs(url($child->ruta)) || request()->is(trim($child->ruta, '/').'*'), // Simple check
                 ];
             })->toArray();

             return [
                 'label' => $item->nombre,
                 'url' => $url, // If has children, url might be #
                 'icon' => $icon,
                 'active' => request()->fullUrlIs($url) || (!empty($mappedChildren) && collect($mappedChildren)->contains('active', true)),
                 'children' => $mappedChildren
             ];
        })->toArray();
    }

    public function index()
    {
        $menuItems = $this->getMenuItems();
        return view('cliente.dashboard', compact('menuItems'));
    }

    public function createReserva()
    {
        $menuItems = $this->getMenuItems();
        return view('cliente.reservas', compact('menuItems'));
    }

    public function getMesasDisponibles(Request $request) 
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
        ]);

        $fecha = $validated['fecha'];
        $horaInicio = Carbon::parse($validated['hora']);
        $horaFin = $horaInicio->copy()->addHours(2);

        // Find mesas that are NOT reserved in this time slot
        // Overlap logic: (StartA < EndB) and (EndA > StartB)
        // We want mesas where NO reservation exists with:
        // (ReservaStart < RequestEnd) AND (ReservaEnd > RequestStart) AND fecha == fecha
        // AND status != cancelada

        $mesasOcupadasIds = Reserva::where('fecha_reserva', $fecha)
            ->where('estado', '!=', 'cancelada')
            ->where(function($query) use ($horaInicio, $horaFin) {
                $query->where('hora_inicio', '<', $horaFin->format('H:i'))
                      ->where('hora_fin', '>', $horaInicio->format('H:i'));
            })
            ->pluck('id_mesa');

        $mesasDisponibles = Mesa::whereNotIn('id_mesa', $mesasOcupadasIds)
                                //->where('estado', 'disponible') // Optional: if physical state matters too
                                ->orderBy('numero_mesa')
                                ->get(['id_mesa', 'numero_mesa', 'capacidad', 'ubicacion']);

        return response()->json($mesasDisponibles);
    }

    public function storeReserva(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'personas' => 'required|integer|min:1',
            'mesa_id' => 'required|exists:mesas,id_mesa', // Validar mesa seleccionada
            'notas' => 'nullable|string'
        ]);

        // Double check availability to prevent race conditions
        $horaInicio = Carbon::parse($validated['hora']);
        $horaFin = $horaInicio->copy()->addHours(2);
        
        $conflict = Reserva::where('fecha_reserva', $validated['fecha'])
            ->where('id_mesa', $validated['mesa_id'])
            ->where('estado', '!=', 'cancelada')
            ->where(function($query) use ($horaInicio, $horaFin) {
                $query->where('hora_inicio', '<', $horaFin->format('H:i'))
                      ->where('hora_fin', '>', $horaInicio->format('H:i'));
            })
            ->exists();
            
        if ($conflict) {
             return back()->with('error', 'La mesa seleccionada ya ha sido reservada en ese horario. Por favor seleccione otra.');
        }

        Reserva::create([
            'fecha_reserva' => $validated['fecha'],
            'hora_inicio' => $horaInicio->format('H:i:s'),
            'hora_fin' => $horaFin->format('H:i:s'),
            'numero_personas' => $validated['personas'],
            'estado' => 'pendiente',
            'observaciones' => $validated['notas'],
            'id_cliente' => Auth::user()->id_usuario,
            'id_mesa' => $validated['mesa_id'],
        ]);

        return redirect()->route('cliente.reservas.index')->with('success', 'Reserva realizada con éxito');
    }

    public function indexReservas()
    {
        $menuItems = $this->getMenuItems();
        $reservas = Reserva::where('id_cliente', Auth::user()->id_usuario)
                           ->orderBy('fecha_reserva', 'desc')
                           ->get();
                           
        return view('cliente.mis-reservas', compact('menuItems', 'reservas'));
    }

    public function pagos()
    {
        $menuItems = $this->getMenuItems();
        // 1. Get confirmed reservations that are not yet paid (assuming logic)
        // For simplicity, let's list "Pendiente" reservations as "Payable"
        // Also could list unpaid orders.
        
        $reservasPendientes = Reserva::where('id_cliente', Auth::user()->id_usuario)
                                     ->where('estado', 'pendiente') // Or 'confirmada' awaiting payment
                                     ->get();

        return view('cliente.pagos', compact('menuItems', 'reservasPendientes')); // Passes reservs instead of orders for now
    }

    public function realizarPago(Request $request)
    {
        $request->validate(['reserva_id' => 'required|exists:reservas,id_reserva']);
        $reserva = Reserva::find($request->reserva_id);
        
        // Simulating a fixed cost per person reservation fee? Or just confirming the reservation.
        // Let's assume a deposit of 50 Bs.
        $total = 50.00;

        // Create Sale
        Venta::create([
            'fecha_venta' => now(),
            'total' => $total,
            'estado' => 'completada',
            'id_usuario' => Auth::user()->id_usuario, // Client self-paying
            'id_cliente' => Auth::user()->id_usuario,
            // 'id_pedido' => null, // No order linked yet
            'id_reserva' => $reserva->id_reserva
        ]);

        // Update Reserva
        $reserva->update(['estado' => 'confirmada']);

        return redirect()->route('cliente.pagos.index')->with('success', 'Pago de reserva realizado con éxito. Reserva confirmada.');
    }
}
