<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Role;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener roles (usar los nombres exactos de la BD)
        $admin = Role::where('nombre_rol', 'gerente')->first();
        $mesero = Role::where('nombre_rol', 'mesero')->first();
        $cajero = Role::where('nombre_rol', 'cajero')->first();
        $cocinero = Role::where('nombre_rol', 'cocinero')->first();

        // Menú para Administrador/Gerente
        if ($admin) {
            $inicioAdmin = MenuItem::create([
                'nombre' => 'Inicio',
                'ruta' => '/admin/dashboard',
                'icono' => 'fa-home',
                'orden' => 1,
                'activo' => true,
            ]);
            $inicioAdmin->roles()->attach($admin->id_rol);

            $usuariosAdmin = MenuItem::create([
                'nombre' => 'Usuarios',
                'ruta' => '/admin/usuarios',
                'icono' => 'fa-users',
                'orden' => 2,
                'activo' => true,
            ]);
            $usuariosAdmin->roles()->attach($admin->id_rol);

            $productosAdmin = MenuItem::create([
                'nombre' => 'Productos',
                'ruta' => '/productos',
                'icono' => 'fa-box',
                'orden' => 3,
                'activo' => true,
            ]);
            $productosAdmin->roles()->attach($admin->id_rol);

            $mesasAdmin = MenuItem::create([
                'nombre' => 'Mesas',
                'ruta' => '/mesas',
                'icono' => 'fa-table',
                'orden' => 4,
                'activo' => true,
            ]);
            $mesasAdmin->roles()->attach($admin->id_rol);

            $estadisticasAdmin = MenuItem::create([
                'nombre' => 'Estadísticas',
                'ruta' => '/admin/reportes',
                'icono' => 'fa-chart-bar',
                'orden' => 5,
                'activo' => true,
            ]);
            $estadisticasAdmin->roles()->attach($admin->id_rol);

            // Funciones de Cajero agregadas al Gerente
            $pagosAdmin = MenuItem::create([
                'nombre' => 'Pagos y Caja',
                'ruta' => '#',
                'icono' => 'fa-cash-register',
                'orden' => 6,
                'activo' => true,
            ]);
            $pagosAdmin->roles()->attach($admin->id_rol);

            // Submenús para Pagos y Caja
            $verPagos = MenuItem::create([
                'nombre' => 'Pagos',
                'ruta' => '/pagos',
                'parent_id' => $pagosAdmin->id,
                'orden' => 1,
                'activo' => true,
            ]);
            $verPagos->roles()->attach($admin->id_rol);

            $verVentas = MenuItem::create([
                'nombre' => 'Ventas',
                'ruta' => '/ventas',
                'parent_id' => $pagosAdmin->id,
                'orden' => 2,
                'activo' => true,
            ]);
            $verVentas->roles()->attach($admin->id_rol);

            $verCajas = MenuItem::create([
                'nombre' => 'Arqueo de Caja',
                'ruta' => '/cajas',
                'parent_id' => $pagosAdmin->id,
                'orden' => 3,
                'activo' => true,
            ]);
            $verCajas->roles()->attach($admin->id_rol);

            $perfilAdmin = MenuItem::create([
                'nombre' => 'Mi Perfil',
                'ruta' => '/dashboard',
                'icono' => 'fa-user',
                'orden' => 7,
                'activo' => true,
            ]);
            $perfilAdmin->roles()->attach($admin->id_rol);
        }

        // Menú para Mesero
        if ($mesero) {
            $dashboardMesero = MenuItem::create([
                'nombre' => 'Dashboard',
                'ruta' => '/dashboard',
                'icono' => 'fa-home',
                'orden' => 1,
                'activo' => true,
            ]);
            $dashboardMesero->roles()->attach($mesero->id_rol);

            $pedidosMesero = MenuItem::create([
                'nombre' => 'Pedidos',
                'ruta' => '/pedidos',
                'icono' => 'fa-clipboard-list',
                'orden' => 2,
                'activo' => true,
            ]);
            $pedidosMesero->roles()->attach($mesero->id_rol);

            $mesasMesero = MenuItem::create([
                'nombre' => 'Mesas',
                'ruta' => '/mesas',
                'icono' => 'fa-table',
                'orden' => 3,
                'activo' => true,
            ]);
            $mesasMesero->roles()->attach($mesero->id_rol);

            $menuMesero = MenuItem::create([
                'nombre' => 'Menú',
                'ruta' => '/menu',
                'icono' => 'fa-utensils',
                'orden' => 4,
                'activo' => true,
            ]);
            $menuMesero->roles()->attach($mesero->id_rol);
        }

        // Menú para Cajero
        if ($cajero) {
            $dashboardCajero = MenuItem::create([
                'nombre' => 'Dashboard',
                'ruta' => '/dashboard',
                'icono' => 'fa-home',
                'orden' => 1,
                'activo' => true,
            ]);
            $dashboardCajero->roles()->attach($cajero->id_rol);

            $pagosCajero = MenuItem::create([
                'nombre' => 'Pagos',
                'ruta' => '/pagos',
                'icono' => 'fa-money-bill-wave',
                'orden' => 2,
                'activo' => true,
            ]);
            $pagosCajero->roles()->attach($cajero->id_rol);

            $ventasCajero = MenuItem::create([
                'nombre' => 'Ventas',
                'ruta' => '/ventas',
                'icono' => 'fa-file-invoice-dollar',
                'orden' => 3,
                'activo' => true,
            ]);
            $ventasCajero->roles()->attach($cajero->id_rol);

            // Cuentas por Cobrar (Nuevo)
            $porCobrar = MenuItem::create([
                'nombre' => 'Cuentas por Cobrar',
                'ruta' => '/ventas/pedidos-pendientes',
                'icono' => 'fa-money-check-alt',
                'orden' => 3, // Misma prioridad o mayor
                'activo' => true,
            ]);
            $porCobrar->roles()->attach($cajero->id_rol);
            if ($admin) $porCobrar->roles()->attach($admin->id_rol);

            $cajasCajero = MenuItem::create([
                'nombre' => 'Cajas',
                'ruta' => '/cajas',
                'icono' => 'fa-cash-register',
                'orden' => 4,
                'activo' => true,
            ]);
            $cajasCajero->roles()->attach($cajero->id_rol);
        }

        // Menú para Cocinero
        if ($cocinero) {
            $dashboardCocinero = MenuItem::create([
                'nombre' => 'Dashboard',
                'ruta' => '/dashboard',
                'icono' => 'fa-home',
                'orden' => 1,
                'activo' => true,
            ]);
            $dashboardCocinero->roles()->attach($cocinero->id_rol);

            $pedidosCocinero = MenuItem::create([
                'nombre' => 'Pedidos en Cocina',
                'ruta' => '/cocina/pedidos',
                'icono' => 'fa-fire',
                'orden' => 2,
                'activo' => true,
            ]);
            $pedidosCocinero->roles()->attach($cocinero->id_rol);
        }
    }
}
