<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener o crear categorías
        $parrilla = Categoria::firstOrCreate(['nombre' => 'Parrilla'], ['descripcion' => 'Cortes de carne', 'tipo' => 'plato']);
        $guarniciones = Categoria::firstOrCreate(['nombre' => 'Guarniciones'], ['descripcion' => 'Acompañamientos', 'tipo' => 'plato']);
        $bebidas = Categoria::firstOrCreate(['nombre' => 'Bebidas'], ['descripcion' => 'Refrescos', 'tipo' => 'bebida']);
        $entradas = Categoria::firstOrCreate(['nombre' => 'Entradas'], ['descripcion' => 'Aperitivos', 'tipo' => 'plato']);

        $productos = [
            // Entradas
            [
                'nombre' => 'Chorizo Parrillero',
                'descripcion' => 'Chorizo criollo a la parrilla con pan casero',
                'precio' => 15.00,
                'tiempo_preparacion' => 10,
                'disponible' => true,
                'id_categoria' => $entradas->id_categoria,
                'imagen' => null,
            ],
            
            // Parrilla (Platos Fuertes)
            [
                'nombre' => 'Churrasco',
                'descripcion' => 'Corte de res jugoso 300g con guarnición',
                'precio' => 60.00,
                'tiempo_preparacion' => 20,
                'disponible' => true,
                'id_categoria' => $parrilla->id_categoria,
                'imagen' => null,
            ],
            [
                'nombre' => 'Bife de Chorizo',
                'descripcion' => 'Bife de chorizo argentino 400g',
                'precio' => 85.00,
                'tiempo_preparacion' => 25,
                'disponible' => true,
                'id_categoria' => $parrilla->id_categoria,
                'imagen' => null,
            ],
            [
                'nombre' => 'Picanha',
                'descripcion' => 'Picanha a la espada',
                'precio' => 70.00,
                'tiempo_preparacion' => 25,
                'disponible' => true,
                'id_categoria' => $parrilla->id_categoria,
                'imagen' => null,
            ],
            [
                'nombre' => 'Pollo a la Parrilla',
                'descripcion' => 'Cuarto de pollo deshuesado a las brasas',
                'precio' => 40.00,
                'tiempo_preparacion' => 20,
                'disponible' => true,
                'id_categoria' => $parrilla->id_categoria,
                'imagen' => null,
            ],

            // Guarniciones
            [
                'nombre' => 'Arroz con Queso',
                'descripcion' => 'Arroz cremoso con queso criollo',
                'precio' => 15.00,
                'tiempo_preparacion' => 5,
                'disponible' => true,
                'id_categoria' => $guarniciones->id_categoria,
                'imagen' => null,
            ],
            [
                'nombre' => 'Yuca Frita',
                'descripcion' => 'Bastones de yuca frita crocante',
                'precio' => 10.00,
                'tiempo_preparacion' => 5,
                'disponible' => true,
                'id_categoria' => $guarniciones->id_categoria,
                'imagen' => null,
            ],

            // Bebidas
            [
                'nombre' => 'Agua Mineral',
                'descripcion' => 'Botella de agua personal 500ml',
                'precio' => 5.00,
                'tiempo_preparacion' => 1,
                'disponible' => true,
                'id_categoria' => $bebidas->id_categoria,
                'imagen' => null,
            ],
            [
                'nombre' => 'Coca Cola',
                'descripcion' => 'Gaseosa original 500ml',
                'precio' => 10.00,
                'tiempo_preparacion' => 1,
                'disponible' => true,
                'id_categoria' => $bebidas->id_categoria,
                'imagen' => null,
            ],
            [
                'nombre' => 'Jarra de Limonada',
                'descripcion' => 'Limonada fresca 1 Litro',
                'precio' => 25.00,
                'tiempo_preparacion' => 5,
                'disponible' => true,
                'id_categoria' => $bebidas->id_categoria,
                'imagen' => null,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
