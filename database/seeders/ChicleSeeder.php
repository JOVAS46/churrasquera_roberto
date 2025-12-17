<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Seeder;

class ChicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Aseguramos que exista una categoría para el chicle, usaremos Postres o una nueva 'Golosinas'
        $categoria = Categoria::firstOrCreate(
            ['nombre' => 'Golosinas'], 
            ['descripcion' => 'Dulces y golosinas varios', 'tipo' => 'plato']
        );

        Producto::create([
            'nombre' => 'Chicle',
            'descripcion' => 'Goma de mascar sabor menta',
            'precio' => 1.00, // 1 Boliviano
            'tiempo_preparacion' => 0,
            'disponible' => true,
            'id_categoria' => $categoria->id_categoria,
        ]);
    }
}
