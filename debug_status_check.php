<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Services\PagoFacilService;

echo "--- Iniciando Prueba de Verificación de Estado (MasterQR V2) ---\n";

$service = new PagoFacilService();

// Usamos un ID dummy o formato válido para ver si el ENDPOINT responde
// Si el endpoint no existe, dará 404. Si existe, dará "Transacción no encontrada" (que es éxito para mi prueba de existencia).
$dummyTransaccion = "123456789"; 

$resultado = $service->consultarEstado($dummyTransaccion);

file_put_contents('status_check_result.txt', print_r($resultado, true));

echo "\n--- Prueba Finalizada. Ver status_check_result.txt ---\n";
