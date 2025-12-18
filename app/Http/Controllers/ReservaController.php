<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Venta;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservaController extends Controller
{
    /**
     * Display a listing of the reservations.
     * Accessible by Admin, Gerente, Mesero.
     */
    public function index(Request $request)
    {
        $query = Reserva::with(['cliente', 'mesa']);

        // Filters
        if ($request->has('fecha')) {
            $query->whereDate('fecha_reserva', $request->fecha);
        } else {
            // Default to today and future? Or just all? 
            // Usually upcoming is more relevant, but admin might want history.
            // Let's default to all, ordered by date desc.
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        $reservas = $query->orderBy('fecha_reserva', 'desc')
                          ->orderBy('hora_inicio', 'asc')
                          ->paginate(15);

        return view('reservas.index', compact('reservas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reserva $reserva)
    {
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
        ]);

        $reserva->update($validated);

        return back()->with('success', 'Estado de reserva actualizado.');
    }
}
