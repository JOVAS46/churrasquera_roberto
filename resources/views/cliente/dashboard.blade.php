@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 text-center">
                    <h2 class="mb-3">Bienvenido, {{ Auth::user()->nombre }}</h2>
                    <p class="text-muted mb-4">¿Qué deseas hacer hoy?</p>
                    
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                        <a href="{{ route('cliente.reservas.create') }}" class="btn btn-primary btn-lg px-4 gap-3">
                            <i class="bi bi-calendar-check me-2"></i>Realizar Reserva
                        </a>
                        <a href="{{ route('cliente.pagos.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="bi bi-credit-card me-2"></i>Pagar Pedido
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><i class="bi bi-info-circle me-2"></i>Estado de Cuenta</h5>
                            <p class="card-text">Revisa tus pedidos pendientes y realiza pagos de forma segura.</p>
                            <a href="{{ route('cliente.pagos.index') }}" class="btn btn-link text-decoration-none p-0">Ir a Pagos &rarr;</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-success"><i class="bi bi-calendar3 me-2"></i>Próxima Visita</h5>
                            <p class="card-text">Reserva una mesa para tu próxima visita y evita esperas.</p>
                            <a href="{{ route('cliente.reservas.create') }}" class="btn btn-link text-decoration-none p-0">Reservar Mesa &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
