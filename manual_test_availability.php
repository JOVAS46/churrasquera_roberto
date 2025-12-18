<?php

use App\Models\Reserva;
use App\Models\Mesa;
use Illuminate\Http\Request;
use Carbon\Carbon;

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Starting Availability Logic Verification...\n";

    // 1. Setup Data
    // Ensure we have a mesa
    $mesa = Mesa::first();
    if (!$mesa) {
        $mesa = Mesa::create(['numero_mesa' => 888, 'capacidad' => 4, 'estado' => 'disponible']);
    }
    echo "Using Mesa ID: " . $mesa->id_mesa . "\n";

    // Create a reservation for TODAY at 14:00 - 16:00
    $today = date('Y-m-d');
    
    // Cleanup existing for clean test
    Reserva::where('fecha_reserva', $today)->where('id_mesa', $mesa->id_mesa)->delete();

    $reserva = Reserva::create([
        'fecha_reserva' => $today,
        'hora_inicio' => '14:00:00',
        'hora_fin' => '16:00:00',
        'numero_personas' => 2,
        'estado' => 'confirmada',
        'id_cliente' => \App\Models\Usuario::first()->id_usuario,
        'id_mesa' => $mesa->id_mesa
    ]);
    echo "Created Reservation: 14:00 - 16:00\n";

    // 2. Test Availability Controller Logic directly
    $controller = new \App\Http\Controllers\ClienteController();

    // Case A: 14:30 (Overlap) -> Should NOT find mesa
    $reqOverlap = new Request(['fecha' => $today, 'hora' => '14:30']);
    // Mocking validate or just reusing connection logic... 
    // Since we can't easily fetch controller output without route dispatch, let's replicate logic block:
    
    $horaReq = Carbon::parse('14:30');
    $horaFinReq = $horaReq->copy()->addHours(2); // 16:30
    
    // Logic from Controller:
    $mesasOcupadasIds = Reserva::where('fecha_reserva', $today)
        ->where('estado', '!=', 'cancelada')
        ->where(function($query) use ($horaReq, $horaFinReq) {
            $query->where('hora_inicio', '<', $horaFinReq->format('H:i'))
                  ->where('hora_fin', '>', $horaReq->format('H:i'));
        })
        ->pluck('id_mesa')->toArray();

    if (in_array($mesa->id_mesa, $mesasOcupadasIds)) {
        echo "Case A (Overlap 14:30): Correctly identified mesa as occupied.\n";
    } else {
        echo "FAILURE Case A: Mesa should be occupied but wasn't.\n";
    }

    // Case B: 17:00 (No Overlap) -> Should find mesa
    $horaReq = Carbon::parse('17:00');
    $horaFinReq = $horaReq->copy()->addHours(2); // 19:00

    $mesasOcupadasIds = Reserva::where('fecha_reserva', $today)
        ->where('estado', '!=', 'cancelada')
        ->where(function($query) use ($horaReq, $horaFinReq) {
            $query->where('hora_inicio', '<', $horaFinReq->format('H:i'))
                  ->where('hora_fin', '>', $horaReq->format('H:i'));
        })
        ->pluck('id_mesa')->toArray();

    if (!in_array($mesa->id_mesa, $mesasOcupadasIds)) {
        echo "Case B (No Overlap 17:00): Correctly identified mesa as available.\n";
    } else {
        echo "FAILURE Case B: Mesa should be available but marked occupied.\n";
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
