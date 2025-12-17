<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Role;
use Illuminate\Database\Seeder;

class InsumoMenuSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('nombre_rol', 'gerente')->first();

        if ($admin) {
            // Verificar si ya existe para no duplicar
            if (!MenuItem::where('nombre', 'Insumos')->exists()) {
                $insumosItem = MenuItem::create([
                    'nombre' => 'Insumos / Stock',
                    'ruta' => '/admin/insumos',
                    'icono' => 'fa-boxes', // Icono de cajas
                    'orden' => 4, // Entre Productos y Mesas
                    'activo' => true,
                ]);
                $insumosItem->roles()->attach($admin->id_rol);
                
                $this->command->info('Menu Insumos agregado correctamente.');
            } else {
                $this->command->info('El menu Insumos ya existe.');
            }
        }
    }
}
