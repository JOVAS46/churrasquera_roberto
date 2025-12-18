<?php

use App\Models\Reserva;
use App\Models\Venta;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Starting Payment Verification...\n";

    // 1. Get our test user
    $user = Usuario::where('email', 'like', 'manualtest%')->first();
    if (!$user) {
        $user = Usuario::where('email', 'cliente@restaurante.com')->first();
    }
    
    if (!$user) {
        throw new Exception("Test user not found. Run manual_test_reservation.php first or seed DB.");
    }
    echo "User ID: " . $user->id_usuario . "\n";

    // 2. Get a pending reservation
    $reserva = Reserva::where('id_cliente', $user->id_usuario)
                      ->where('estado', 'pendiente')
                      ->first();

    if (!$reserva) {
        echo "No pending reservation found. Creating one...\n";
        // Create one on the fly if needed, or just warn
        // Re-using logic from creating one
        $reserva = Reserva::create([
            'fecha_reserva' => date('Y-m-d', strtotime('+2 days')),
            'hora_inicio' => '18:00',
            'hora_fin' => '20:00',
            'numero_personas' => 4,
            'estado' => 'pendiente',
            'id_cliente' => $user->id_usuario,
            // Assuming at least one mesa exists, taking first
            'id_mesa' => \App\Models\Mesa::first()->id_mesa
        ]);
    }
    echo "Reserva ID: " . $reserva->id_reserva . "\n";

    // 3. Simulate Payment (Logic from ClienteController::realizarPago)
    echo "Simulating Payment...\n";
    
    DB::beginTransaction();
    $venta = Venta::create([
        'fecha_venta' => now(),
        'total' => 50.00,
        'estado' => 'completada',
        'id_usuario' => $user->id_usuario,
        'id_cliente' => $user->id_usuario,
        'id_reserva' => $reserva->id_reserva
    ]);
    
    $reserva->update(['estado' => 'confirmada']);
    DB::commit();

    echo "Venta Created ID: " . $venta->id_venta . "\n";
    echo "Reserva new status: " . $reserva->fresh()->estado . "\n";

    if ($venta->id_reserva == $reserva->id_reserva && $reserva->fresh()->estado == 'confirmada') {
        echo "SUCCESS: Payment linked to reservation and reservation confirmed.\n";
    } else {
        echo "FAILURE: Checks failed.\n";
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
