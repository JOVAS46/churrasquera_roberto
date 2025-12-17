<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nombre_rol' => 'gerente', // Standarized to lowercase 'gerente'
                'descripcion' => 'Administrador del sistema con acceso completo',
            ],
            [
                'nombre_rol' => 'mesero', // Lowercase consistency
                'descripcion' => 'Encargado de tomar pedidos y atender mesas',
            ],
            [
                'nombre_rol' => 'cajero', // New/Ensured role
                'descripcion' => 'Encargado de procesar pagos y gestionar caja',
            ],
            [
                'nombre_rol' => 'cocinero', // Lowercase consistency
                'descripcion' => 'Encargado de preparar los pedidos',
            ],
            // 'cliente' role removed as unnecessary
        ];

        foreach ($roles as $rol) {
            \App\Models\Role::firstOrCreate(
                ['nombre_rol' => $rol['nombre_rol']],
                ['descripcion' => $rol['descripcion']]
            );
        }
    }
}
