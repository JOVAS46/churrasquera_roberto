<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "--- LISTADO DE ROLES ---\n";
$roles = App\Models\Role::all();
foreach ($roles as $role) {
    echo "ID: {$role->id_rol}, Nombre: '{$role->nombre_rol}'\n";
}
echo "----------------------\n";

echo "\n--- INTENTO DE SEEDER MANUAL ---\n";
try {
    $admin = App\Models\Role::where('nombre_rol', 'gerente')->first() ?? App\Models\Role::where('nombre_rol', 'Administrador')->first();
    echo "Admin: " . ($admin ? "ENCONTRADO ({$admin->nombre_rol})" : "NO ENCONTRADO") . "\n";
    
    $mesero = App\Models\Role::where('nombre_rol', 'mesero')->first() ?? App\Models\Role::where('nombre_rol', 'Mesero')->first();
    echo "Mesero: " . ($mesero ? "ENCONTRADO ({$mesero->nombre_rol})" : "NO ENCONTRADO") . "\n";
    
    $cajero = App\Models\Role::where('nombre_rol', 'cajero')->first() ?? App\Models\Role::where('nombre_rol', 'Cajero')->first();
    echo "Cajero: " . ($cajero ? "ENCONTRADO ({$cajero->nombre_rol})" : "NO ENCONTRADO") . "\n";
    
    $cocinero = App\Models\Role::where('nombre_rol', 'cocinero')->first() ?? App\Models\Role::where('nombre_rol', 'Cocinero')->first();
    echo "Cocinero: " . ($cocinero ? "ENCONTRADO ({$cocinero->nombre_rol})" : "NO ENCONTRADO") . "\n";
    
    $cliente = App\Models\Role::where('nombre_rol', 'cliente')->first() ?? App\Models\Role::where('nombre_rol', 'Cliente')->first();
    echo "Cliente: " . ($cliente ? "ENCONTRADO ({$cliente->nombre_rol})" : "NO ENCONTRADO") . "\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
