<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PagoFacilService
{
    protected $tokenService;
    protected $tokenSecret;
    // Base URL para MasterQR v2
    protected $baseUrl = 'https://masterqr.pagofacil.com.bo/api/services/v2';

    public function __construct()
    {
        $this->tokenService = config('services.pagofacil.token_service');
        $this->tokenSecret = config('services.pagofacil.token_secret');
    }

    /**
     * Autenticarse y obtener Token Bearer
     */
    private function login()
    {
        try {
            $response = Http::withHeaders([
                'tcTokenService' => $this->tokenService,
                'tcTokenSecret' => $this->tokenSecret,
            ])->post($this->baseUrl . '/login');

            if ($response->successful()) {
                $data = $response->json();
                // Estructura: values -> accessToken
                return $data['values']['accessToken'] ?? null;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generar QR para una venta (Versión 2 JWT)
     */
    public function generarQR(int $idVenta, float $monto, string $clienteEmail = 'cajero@restaurante.com')
    {
        try {
            // 1. Obtener Token
            $jwtToken = $this->login();
            
            if (!$jwtToken) {
                return [
                    'success' => false,
                    'message' => 'No se pudo autenticar con PagoFácil (Login fallido).',
                ];
            }

            // 2. Preparar payload para Generate QR V2
            $payload = [
                "paymentMethod" => 4, // 4 = QR
                "clientName" => "Cliente Venta " . $idVenta,
                "documentType" => 1,
                "documentId" => "0000000",
                "phoneNumber" => "00000000",
                "email" => $clienteEmail,
                "paymentNumber" => "V-" . $idVenta . "-" . time(), // Debe ser único
                "amount" => $monto,
                "currency" => 2, // 2 = Bs (Bolivianos)
                "clientCode" => "11001", // Cod Cliente fijo o variable según integración
                "callbackUrl" => config('services.pagofacil.callback_url'), // URL desde config/env
                "orderDetail" => [
                    [
                        "serial" => 1,
                        "product" => "Consumo Venta #" . $idVenta,
                        "quantity" => 1,
                        "price" => $monto,
                        "discount" => 0,
                        "total" => $monto
                    ]
                ]
            ];

            // 3. Enviar Solicitud con Bearer Token
            $response = Http::withToken($jwtToken)->post($this->baseUrl . '/generate-qr', $payload);
            $data = $response->json();

            // 4. Procesar Respuesta
            if (isset($data['error']) && $data['error'] == 0) {
                $values = $data['values'];
                // QR en base64 viene en 'qrBase64'
                $qrImage = $values['qrBase64'] ?? null; 
                $nroTransaccion = $values['transactionId'] ?? null;

                return [
                    'success' => true,
                    'qr_image' => $qrImage,
                    'nro_transaccion' => $nroTransaccion,
                    'message' => $data['message'] ?? 'QR Generado',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $data['message'] ?? 'Error al generar QR',
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Excepción: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Consultar estado de una transacción
     */
    public function consultarEstado(string $nroTransaccion)
    {
        try {
            $jwtToken = $this->login();
            
            if (!$jwtToken) {
                return ['success' => false, 'message' => 'Error de autenticación'];
            }

            // Consultar transacción
            // Endpoint V2 puede variar, usamos /query-transaction con POST y transactionId según patrón V2
            // O GET con param. Probamos POST que es común en esta suite.
            // Corregido según error API: espera 'tnTransaccionPF'
            $response = Http::withToken($jwtToken)->post($this->baseUrl . '/query-transaction', [
                'tnTransaccionPF' => $nroTransaccion
            ]);

            $data = $response->json();

            if (isset($data['error']) && $data['error'] == 0) {
                return [
                    'success' => true,
                    'data' => $data,
                    // Si el estado viene en values
                    'status' => $data['values']['status'] ?? null 
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $data['message'] ?? 'Error al consultar',
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Excepción: ' . $e->getMessage(),
            ];
        }
    }
}
