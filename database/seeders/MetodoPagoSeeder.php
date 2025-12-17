<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use Illuminate\Database\Seeder;

class MetodoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metodos = [
            [
                'nombre' => 'Efectivo',
                'descripcion' => 'Pago en efectivo',
                'activo' => true,
            ],
            [
                'nombre' => 'Tarjeta de Crédito',
                'descripcion' => 'Pago con tarjeta de crédito',
                'activo' => true,
            ],
            [
                'nombre' => 'Tarjeta de Débito',
                'descripcion' => 'Pago con tarjeta de débito',
                'activo' => true,
            ],
            [
                'nombre' => 'QR PagoFácil',
                'descripcion' => 'Pago mediante código QR de PagoFácil',
                'activo' => true,
            ],
            [
                'nombre' => 'Transferencia Bancaria',
                'descripcion' => 'Transferencia bancaria',
                'activo' => true,
            ],
        ];

        foreach ($metodos as $metodo) {
            MetodoPago::firstOrCreate(
                ['nombre' => $metodo['nombre']],
                [
                    'descripcion' => $metodo['descripcion'],
                    'activo' => $metodo['activo']
                ]
            );
        }
    }
}
