<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mesas = Mesa::orderBy('numero_mesa')->get();
        
        // Contar mesas por estado
        $stats = [
            'total' => $mesas->count(),
            'disponibles' => $mesas->where('estado', 'disponible')->count(),
            'ocupadas' => $mesas->where('estado', 'ocupada')->count(),
            'reservadas' => $mesas->where('estado', 'reservada')->count(),
        ];
        
        return view('mesas.index', compact('mesas', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mesas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_mesa' => 'required|integer|unique:mesas,numero_mesa',
            'capacidad' => 'required|integer|min:1|max:20',
            'estado' => 'required|in:disponible,ocupada,reservada',
        ]);

        Mesa::create($validated);

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mesa $mesa)
    {
        $mesa->load('pedidos');
        return view('mesas.show', compact('mesa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mesa $mesa)
    {
        return view('mesas.edit', compact('mesa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mesa $mesa)
    {
        $validated = $request->validate([
            'numero_mesa' => 'required|integer|unique:mesas,numero_mesa,' . $mesa->id_mesa . ',id_mesa',
            'capacidad' => 'required|integer|min:1|max:20',
            'estado' => 'required|in:disponible,ocupada,reservada',
        ]);

        $mesa->update($validated);

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mesa $mesa)
    {
        $mesa->delete();

        return redirect()->route('mesas.index')
            ->with('success', 'Mesa eliminada exitosamente.');
    }
    
    /**
     * Cambiar estado de la mesa
     */
    public function cambiarEstado(Request $request, Mesa $mesa)
    {
        $validated = $request->validate([
            'estado' => 'required|in:disponible,ocupada,reservada',
        ]);

        $mesa->update(['estado' => $validated['estado']]);

        return redirect()->route('mesas.index')
            ->with('success', 'Estado de mesa actualizado exitosamente.');
    }
}
