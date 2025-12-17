<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Insumo;
use App\Models\Receta;
use Illuminate\Http\Request;

class RecetaController extends Controller
{
    /**
     * Mostrar la receta de un producto (Ingredientes asignados)
     */
    public function edit($idProducto)
    {
        $producto = Producto::with('insumos')->findOrFail($idProducto);
        $insumos = Insumo::orderBy('nombre')->get(); // Todos los insumos para el select
        
        return view('recetas.edit', compact('producto', 'insumos'));
    }

    /**
     * Agregar un ingrediente a la receta
     */
    public function store(Request $request, $idProducto)
    {
        $request->validate([
            'id_insumo' => 'required|exists:insumos,id_insumo',
            'cantidad_requerida' => 'required|numeric|min:0.001',
        ]);

        $producto = Producto::findOrFail($idProducto);

        // Verificar si ya existe el insumo en la receta
        if ($producto->insumos()->where('insumos.id_insumo', $request->id_insumo)->exists()) {
            return back()->with('error', 'El insumo ya está en la receta.');
        }

        $producto->insumos()->attach($request->id_insumo, [
            'cantidad_requerida' => $request->cantidad_requerida
        ]);

        return back()->with('success', 'Ingrediente agregado a la receta.');
    }

    /**
     * Quitar un ingrediente de la receta
     */
    public function destroy($idProducto, $idInsumo)
    {
        $producto = Producto::findOrFail($idProducto);
        $producto->insumos()->detach($idInsumo);

        return back()->with('success', 'Ingrediente eliminado de la receta.');
    }
}
