<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\MenuItem;
use App\Models\Role;

echo "Agregando menú 'Cuentas por Cobrar'...\n";

$roles = Role::whereIn('nombre_rol', ['gerente', 'cajero', 'Administrador'])->get();

// Buscar el padre "Pagos y Caja" (O crear uno si no existe, pero asumimos que sí por el seeder)
$parent = MenuItem::where('nombre', 'Pagos y Caja')->first();

if (!$parent) {
    // Fallback: crear en root
    $parentId = null;
    echo "No se encontró menú padre 'Pagos y Caja', creando en raíz.\n";
} else {
    $parentId = $parent->id;
}

// Verificar si ya existe
if (MenuItem::where('ruta', '/ventas/pedidos-pendientes')->exists()) {
    echo "El menú ya existe.\n";
    exit;
}

$newItem = MenuItem::create([
    'nombre' => 'Cuentas por Cobrar',
    'ruta' => '/ventas/pedidos-pendientes',
    'icono' => 'bi bi-cash-stack', // Bootstrap icon
    'parent_id' => $parentId,
    'orden' => 0, // Primero
    'activo' => true,
]);

foreach ($roles as $rol) {
    $newItem->roles()->attach($rol->id_rol);
    echo "Asignado a rol: " . $rol->nombre_rol . "\n";
}

echo "Menú agregado correctamente.\n";
