<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Parrilla',
                'descripcion' => 'Cortes de carne a la parrilla',
                'tipo' => 'plato',
            ],
            [
                'nombre' => 'Guarniciones',
                'descripcion' => 'Acompañamientos para la carne',
                'tipo' => 'plato',
            ],
            [
                'nombre' => 'Bebidas',
                'descripcion' => 'Refrescos, agua y gaseosas',
                'tipo' => 'bebida',
            ],
            [
                'nombre' => 'Entradas',
                'descripcion' => 'Chorizos y aperitivos',
                'tipo' => 'plato',
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::firstOrCreate(
                ['nombre' => $categoria['nombre']],
                [
                    'descripcion' => $categoria['descripcion'],
                    'tipo' => $categoria['tipo'],
                ]
            );
        }
    }
}
