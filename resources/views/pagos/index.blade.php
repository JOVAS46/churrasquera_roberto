@extends('layouts.app')

@section('title', 'Pagos')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2"><i class="bi bi-credit-card"></i> Gestión de Pagos</h1>
            <p class="text-muted">Procesa pagos de pedidos</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-credit-card text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3">Sistema de Pagos</h4>
            <p class="text-muted">
                Procesa pagos con múltiples métodos: Efectivo, Tarjetas, QR PagoFácil y Transferencias.
            </p>
            <div class="alert alert-warning mt-4">
                <i class="bi bi-exclamation-triangle"></i> 
                <strong>Pendiente de configuración</strong><br>
                Se requieren las API keys de PagoFácil para activar los pagos electrónicos.
            </div>
        </div>
    </div>
</div>
@endsection
