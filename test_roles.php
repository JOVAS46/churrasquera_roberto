<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Roles en la base de datos:\n";
$roles = App\Models\Role::all();
foreach ($roles as $role) {
    echo "ID: {$role->id_rol}, Nombre: {$role->nombre_rol}\n";
}

echo "\nProbando crear un menu item...\n";
try {
    $mesero = App\Models\Role::where('nombre_rol', 'mesero')->first();
    if ($mesero) {
        echo "Rol mesero encontrado: ID {$mesero->id_rol}\n";
    } else {
        echo "Rol mesero NO encontrado\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
