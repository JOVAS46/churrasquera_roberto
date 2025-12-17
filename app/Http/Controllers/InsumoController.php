<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    /**
     * Listado de insumos (Inventario)
     */
    public function index()
    {
        $insumos = Insumo::orderBy('nombre')->simplePaginate(15);
        return view('insumos.index', compact('insumos'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('insumos.create');
    }

    /**
     * Guardar nuevo insumo
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'unidad_medida' => 'required|string|max:20',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        Insumo::create($validated);

        return redirect()->route('admin.insumos.index')
            ->with('success', 'Insumo registrado correctamente.');
    }

    /**
     * Formulario de edición
     */
    public function edit(Insumo $insumo)
    {
        return view('insumos.edit', compact('insumo'));
    }

    /**
     * Actualizar insumo (Inventario manual)
     */
    public function update(Request $request, Insumo $insumo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'unidad_medida' => 'required|string|max:20',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        $insumo->update($validated);

        return redirect()->route('admin.insumos.index')
            ->with('success', 'Inventario actualizado.');
    }

    /**
     * Eliminar insumo
     */
    public function destroy(Insumo $insumo)
    {
        $insumo->delete();
        return redirect()->route('admin.insumos.index')
            ->with('success', 'Insumo eliminado.');
    }
}
