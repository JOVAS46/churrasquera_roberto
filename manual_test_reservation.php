<?php

use App\Models\Mesa;
use App\Models\Role;
use App\Models\Usuario;
use App\Models\Reserva;
use Illuminate\Support\Facades\Auth;

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Starting Manual Test...\n";

    // 1. Ensure Role
    $role = Role::firstOrCreate(['nombre_rol' => 'cliente'], ['descripcion' => 'Cliente Test']);
    echo "Role 'cliente' ID: " . $role->id_rol . "\n";

    // 2. Ensure Mesa
    $mesa = Mesa::firstOrCreate(
        ['numero_mesa' => 999], 
        ['capacidad' => 4, 'estado' => 'disponible', 'ubicacion' => 'salon']
    );
    echo "Mesa ID: " . $mesa->id_mesa . "\n";

    // 3. Create User
    $email = 'manualtest' . time() . '@test.com';
    $cliente = Usuario::create([
        'nombre' => 'Manual',
        'apellido' => 'Test',
        'email' => $email,
        'password' => bcrypt('password'),
        'telefono' => '12345678',
        'estado' => true,
        'id_rol' => $role->id_rol
    ]);
    echo "User Created: " . $cliente->id_usuario . "\n";

    // 4. Mimic logic from Controller
    // Controller logic:
    // $mesa = Mesa::where('estado', 'disponible')->first();
    // ...
    // Reserva::create(...)

    echo "Attempting to create Reserva...\n";
    
    $reserva = Reserva::create([
        'fecha_reserva' => date('Y-m-d', strtotime('+1 day')),
        'hora_inicio' => '14:00:00',
        'hora_fin' => '16:00:00',
        'numero_personas' => 2,
        'estado' => 'pendiente',
        'observaciones' => 'Manual Test Note',
        'id_cliente' => $cliente->id_usuario,
        'id_mesa' => $mesa->id_mesa,
    ]);

    echo "Reserva Created ID: " . $reserva->id_reserva . "\n";
    echo "SUCCESS!\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
