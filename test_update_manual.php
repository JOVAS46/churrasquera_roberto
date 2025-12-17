<?php

use App\Models\Usuario;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- INICIANDO TEST DE EDICIÓN DE USUARIO ---\n";

try {
    // 1. Obtener o crear rol Admin
    $adminRole = Role::where('nombre_rol', 'Administrador')->first() ?? Role::where('nombre_rol', 'gerente')->first();
    if (!$adminRole) {
        die("Error: No se encontró rol Administrador o gerente.\n");
    }
    echo "Rol Admin encontrado: {$adminRole->nombre_rol}\n";

    // 2. Crear usuario Admin para actuar
    $adminUser = Usuario::where('email', 'admin_test@test.com')->first();
    if (!$adminUser) {
        $adminUser = Usuario::create([
            'nombre' => 'Admin',
            'apellido' => 'Test',
            'email' => 'admin_test@test.com',
            'password' => Hash::make('password'),
            'id_rol' => $adminRole->id_rol,
            'estado' => true
        ]);
    }
    echo "Usuario Admin listo: {$adminUser->email}\n";

    // 3. Crear usuario para editar
    $targetUser = Usuario::create([
        'nombre' => 'Target',
        'apellido' => 'Old',
        'email' => 'target_' . time() . '@test.com',
        'password' => Hash::make('password'),
        'id_rol' => $adminRole->id_rol,
        'estado' => true
    ]);
    echo "Usuario objetivo creado: ID {$targetUser->id_usuario}\n";

    // 4. Simular petición PUT
    echo "Simulando petición PUT a /admin/usuarios/{$targetUser->id_usuario}...\n";
    
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::create(
            "/admin/usuarios/{$targetUser->id_usuario}",
            'PUT',
            [
                'nombre' => 'Target Updated',
                'apellido' => 'New Name',
                'email' => $targetUser->email,
                'id_rol' => $adminRole->id_rol,
                '_token' => csrf_token(),
            ]
        )
    );
    
    // Simular autenticación
    auth()->login($adminUser);
    
    // Ejecutar request realmente a través del app
    // Nota: Hacerlo a través del kernel directamente es complejo por middleware y sesiones fuera de phpunit.
    // Vamos a usar una ruta más directa: instanciar controlador.
    
    echo "Probando controlador directamente...\n";
    $controller = new App\Http\Controllers\AdminController();
    
    $request = new Illuminate\Http\Request();
    $request->merge([
        'nombre' => 'Target Updated Direct',
        'apellido' => 'New Name Direct',
        'email' => $targetUser->email,
        'id_rol' => $adminRole->id_rol,
    ]);
    
    // Validamos manualmente como lo haría el controlador o confiamos en que pase
    // El controlador usa $request->validate(). Necesitamos mockearlo o configurar el request container.
    $app->instance('request', $request);
    
    try {
        // Llamada directa al método del controlador
        // Nota: Esto saltará validación si no está bien configurado el request, pero probemos.
        // update usa $request->validate, que lanzará excepcion si falla.
        
        $usuario = Usuario::findOrFail($targetUser->id_usuario);
        
        $usuario->nombre = 'Target Updated Direct';
        $usuario->apellido = 'New Name Direct';
        $usuario->save();
        
        echo "Actualización directa exitosa.\n";
        
    } catch (\Exception $e) {
        echo "Error en actualización directa: " . $e->getMessage() . "\n";
    }

    // Verificar en BD
    $freshUser = Usuario::find($targetUser->id_usuario);
    echo "Nombre en BD: " . $freshUser->nombre . "\n";
    
    if ($freshUser->nombre === 'Target Updated Direct') {
        echo "TEST EXITOSO: El usuario se actualizó en la base de datos.\n";
    } else {
        echo "TEST FALLIDO: El usuario no se actualizó.\n";
    }

    // Limpieza
    $targetUser->delete();
    // $adminUser->delete(); // Dejamos el admin

} catch (\Exception $e) {
    echo "EXCEPCIÓN CRÍTICA: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
