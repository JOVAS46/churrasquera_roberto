<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICACIÓN DE BASE DE DATOS ===\n\n";

echo "1. Conexión configurada: " . config('database.default') . "\n";
echo "2. Base de datos: " . config('database.connections.' . config('database.default') . '.database') . "\n";
echo "3. Host: " . config('database.connections.' . config('database.default') . '.host') . "\n";
echo "4. Puerto: " . config('database.connections.' . config('database.default') . '.port') . "\n\n";

try {
    $pdo = DB::connection()->getPdo();
    echo "✅ Conexión exitosa\n";
    echo "5. Base de datos actual: " . DB::connection()->getDatabaseName() . "\n\n";
    
    // Verificar si existe la tabla usuarios
    $tableExists = DB::select("SELECT to_regclass('public.usuarios') as exists");
    if ($tableExists[0]->exists) {
        echo "✅ Tabla 'usuarios' existe\n\n";
        
        // Contar usuarios
        $count = DB::table('usuarios')->count();
        echo "6. Total usuarios en BD: " . $count . "\n\n";
        
        // Buscar usuario mesero
        $user = DB::table('usuarios')->where('email', 'mesero@restaurante.com')->first();
        if ($user) {
            echo "✅ Usuario mesero encontrado:\n";
            echo "   - ID: " . $user->id_usuario . "\n";
            echo "   - Nombre: " . $user->nombre . " " . $user->apellido . "\n";
            echo "   - Email: " . $user->email . "\n";
            echo "   - Rol ID: " . $user->id_rol . "\n";
        } else {
            echo "❌ Usuario mesero NO encontrado\n";
        }
    } else {
        echo "❌ Tabla 'usuarios' NO existe\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
}
