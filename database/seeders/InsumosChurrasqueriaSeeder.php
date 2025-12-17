<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insumo;
use App\Models\Producto;

class InsumosChurrasqueriaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Insumos
        $insumos = [
            ['nombre' => 'Carne de Res (Lomo)', 'unidad_medida' => 'kg', 'stock_actual' => 50, 'stock_minimo' => 10, 'precio_unitario' => 35],
            ['nombre' => 'Pollo Entero', 'unidad_medida' => 'kg', 'stock_actual' => 30, 'stock_minimo' => 5, 'precio_unitario' => 15],
            ['nombre' => 'Arroz', 'unidad_medida' => 'kg', 'stock_actual' => 100, 'stock_minimo' => 20, 'precio_unitario' => 5],
            ['nombre' => 'Yuca', 'unidad_medida' => 'kg', 'stock_actual' => 80, 'stock_minimo' => 15, 'precio_unitario' => 3],
            ['nombre' => 'Queso Criollo', 'unidad_medida' => 'kg', 'stock_actual' => 20, 'stock_minimo' => 2, 'precio_unitario' => 25],
            ['nombre' => 'Chorizo Parrillero', 'unidad_medida' => 'unidad', 'stock_actual' => 100, 'stock_minimo' => 20, 'precio_unitario' => 3],
            ['nombre' => 'Pan Casero', 'unidad_medida' => 'unidad', 'stock_actual' => 200, 'stock_minimo' => 50, 'precio_unitario' => 0.5],
            ['nombre' => 'Coca Cola 500ml', 'unidad_medida' => 'unidad', 'stock_actual' => 200, 'stock_minimo' => 48, 'precio_unitario' => 5], // 5Bs costo compra
            ['nombre' => 'Limones', 'unidad_medida' => 'kg', 'stock_actual' => 10, 'stock_minimo' => 2, 'precio_unitario' => 4],
            ['nombre' => 'Azúcar', 'unidad_medida' => 'kg', 'stock_actual' => 20, 'stock_minimo' => 5, 'precio_unitario' => 5],
            ['nombre' => 'Maní', 'unidad_medida' => 'kg', 'stock_actual' => 15, 'stock_minimo' => 2, 'precio_unitario' => 10],
        ];

        $insumoMap = [];
        foreach ($insumos as $data) {
            $insumo = Insumo::firstOrCreate(['nombre' => $data['nombre']], $data);
            $insumoMap[$data['nombre']] = $insumo->id_insumo;
        }

        $this->command->info('Insumos creados correctamente.');

        // 2. Asignar Recetas a Productos Existentes
        
        // Churrasco (0.4kg Carne + 0.1kg Arroz + 0.2kg Yuca)
        $this->asignarReceta('Churrasco', [
            'Carne de Res (Lomo)' => 0.400,
            'Arroz' => 0.100,
            'Yuca' => 0.200,
        ], $insumoMap);

        // Bife de Chorizo (0.5kg Carne + Yuca)
        $this->asignarReceta('Bife de Chorizo', [
            'Carne de Res (Lomo)' => 0.500,
            'Yuca' => 0.250,
        ], $insumoMap);

        // Pollo a la Parrilla (0.5kg Pollo + Arroz)
        $this->asignarReceta('Pollo a la Parrilla', [
            'Pollo Entero' => 0.500,
            'Arroz' => 0.150,
        ], $insumoMap);

        // Chorizo Parrillero (1 Chorizo + 1 Pan)
        $this->asignarReceta('Chorizo Parrillero', [
            'Chorizo Parrillero' => 1,
            'Pan Casero' => 1,
        ], $insumoMap);

        // Arroz con Queso (0.2kg Arroz + 0.05kg Queso)
        $this->asignarReceta('Arroz con Queso', [
            'Arroz' => 0.200,
            'Queso Criollo' => 0.050,
        ], $insumoMap);

        // Yuca Frita
        $this->asignarReceta('Yuca Frita', [
            'Yuca' => 0.300,
        ], $insumoMap);

        // Coca Cola
        $this->asignarReceta('Coca Cola', [
            'Coca Cola 500ml' => 1,
        ], $insumoMap);

        // Jarra Limonada (0.5kg Limon + 0.1kg Azucar)
        $this->asignarReceta('Jarra de Limonada', [
            'Limones' => 0.500,
            'Azúcar' => 0.100,
        ], $insumoMap);

        $this->command->info('Recetas asignadas con éxito.');
    }

    private function asignarReceta($nombreProducto, $ingredientes, $insumoMap)
    {
        $producto = Producto::where('nombre', 'like', "%$nombreProducto%")->first();
        if ($producto) {
            $syncData = [];
            foreach ($ingredientes as $nombreInsumo => $cantidad) {
                if (isset($insumoMap[$nombreInsumo])) {
                    $syncData[$insumoMap[$nombreInsumo]] = ['cantidad_requerida' => $cantidad];
                }
            }
            if (!empty($syncData)) {
                $producto->insumos()->syncWithoutDetaching($syncData);
                $this->command->info("Receta configurada para: $producto->nombre");
            }
        }
    }
}
