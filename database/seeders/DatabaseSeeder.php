<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear roles primero
        $this->call(RoleSeeder::class);
        
        // 2. Crear usuarios con roles específicos
        $gerenteRole = \App\Models\Role::where('nombre_rol', 'gerente')->first();
        $meseroRole = \App\Models\Role::where('nombre_rol', 'mesero')->first();
        $cajeroRole = \App\Models\Role::where('nombre_rol', 'cajero')->first();
        $cocineroRole = \App\Models\Role::where('nombre_rol', 'cocinero')->first();
        
        // Usuario Gerente
        if ($gerenteRole) {
            \App\Models\Usuario::firstOrCreate(
                ['email' => 'admin@restaurante.com'],
                [
                    'nombre' => 'Admin',
                    'apellido' => 'Gerente',
                    'telefono' => '70000000',
                    'password' => bcrypt('password'),
                    'estado' => true,
                    'id_rol' => $gerenteRole->id_rol,
                ]
            );
        }
        
        // Usuario Mesero
        if ($meseroRole) {
            \App\Models\Usuario::firstOrCreate(
                ['email' => 'mesero@restaurante.com'],
                [
                    'nombre' => 'Juan',
                    'apellido' => 'Mesero',
                    'telefono' => '70123456',
                    'password' => bcrypt('password'),
                    'estado' => true,
                    'id_rol' => $meseroRole->id_rol,
                ]
            );
        }
        
        // Usuario Cajero
        if ($cajeroRole) {
            \App\Models\Usuario::firstOrCreate(
                ['email' => 'cajero@restaurante.com'],
                [
                    'nombre' => 'María',
                    'apellido' => 'Cajero',
                    'telefono' => '70234567',
                    'password' => bcrypt('password'),
                    'estado' => true,
                    'id_rol' => $cajeroRole->id_rol,
                ]
            );
        }
        
        // Usuario Cocinero
        if ($cocineroRole) {
            \App\Models\Usuario::firstOrCreate(
                ['email' => 'cocinero@restaurante.com'],
                [
                    'nombre' => 'Carlos',
                    'apellido' => 'Cocinero',
                    'telefono' => '70345678',
                    'password' => bcrypt('password'),
                    'estado' => true,
                    'id_rol' => $cocineroRole->id_rol,
                ]
            );
        }
        
        // 3. Crear menú dinámico
        $this->call(MenuItemSeeder::class);
        
        // 4. Crear métodos de pago
        $this->call(MetodoPagoSeeder::class);
        
        // 5. Crear categorías
        $this->call(CategoriaSeeder::class);
        
        // 6. Crear productos
        $this->call(ProductoSeeder::class);
        
        // 7. Crear mesas
        $this->call(MesaSeeder::class);
    }
}
