<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Producto::with('categoria');

        // Búsqueda
        if ($request->has('buscar') && $request->buscar != '') {
            $query->buscar($request->buscar);
        }

        // Filtro por categoría
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('id_categoria', $request->categoria);
        }

        // Filtro por disponibilidad
        if ($request->has('disponible')) {
            $query->disponible();
        }

        $productos = $query->orderBy('nombre')->paginate(12);
        $categorias = Categoria::all();

        return view('productos.index', compact('productos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable',
            'precio' => 'required|numeric|min:0',
            'tiempo_preparacion' => 'nullable|integer|min:0',
            'disponible' => 'boolean',
            'id_categoria' => 'required|exists:categorias,id_categoria',
        ]);

        $producto = Producto::create($validated);

        // Redirigir a la edición de receta inmediatamente para facilitar el flujo
        return redirect()->route('admin.recetas.edit', $producto->id_producto)
            ->with('success', 'Producto creado. Ahora asigna los ingredientes (Insumos) que consume.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'nombre' => 'required|max:100',
            'descripcion' => 'nullable',
            'precio' => 'required|numeric|min:0',
            'tiempo_preparacion' => 'nullable|integer|min:0',
            'disponible' => 'boolean',
            'id_categoria' => 'required|exists:categorias,id_categoria',
        ]);

        $producto->update($validated);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}
