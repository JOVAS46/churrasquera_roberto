<?php

use App\Models\Role;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    DB::beginTransaction();

    echo "Cleaning tables...\n";
    // DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // MySQL
    // DB::statement('SET session_replication_role = replica;'); // PostgreSQL

    // Role::truncate();
    // MenuItem::truncate();

    echo "Seeding roles...\n";
    $roles = [
        ['nombre_rol' => 'gerente', 'descripcion' => 'Admin system'],
        ['nombre_rol' => 'mesero', 'descripcion' => 'Waiter'],
        ['nombre_rol' => 'cajero', 'descripcion' => 'Cashier'],
        ['nombre_rol' => 'cocinero', 'descripcion' => 'Cook'],
    ];

    foreach ($roles as $rolData) {
        $r = Role::firstOrCreate(['nombre_rol' => $rolData['nombre_rol']], $rolData);
        echo "Role created: " . $r->nombre_rol . " ID: " . $r->id_rol . "\n";
    }

    echo "Seeding Menu Items...\n";
    $admin = Role::where('nombre_rol', 'gerente')->first();
    
    if (!$admin) {
        throw new Exception("Admin role not found!");
    }

    $itemCheck = MenuItem::create([
        'nombre' => 'Test Item',
        'ruta' => '/test',
        'icono' => 'fa-test',
        'orden' => 1,
        'activo' => true,
    ]);
    echo "Menu item created ID: " . $itemCheck->id_menu . "\n";
    
    $itemCheck->roles()->attach($admin->id_rol);
    echo "Attached to role ID: " . $admin->id_rol . "\n";

    DB::rollBack(); // Don't actually save, just test logic
    echo "\nSUCCESS: Seeding logic seems valid.\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    if ($e instanceof Illuminate\Database\QueryException) {
        echo "SQL: " . $e->getSql() . "\n";
    }
}
