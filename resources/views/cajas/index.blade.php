@extends('layouts.app')

@section('title', 'Control de Caja')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2"><i class="bi bi-cash-register"></i> Control de Caja</h1>
            <p class="text-muted">Administra el flujo de efectivo del restaurante</p>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-success">
                <i class="bi bi-box-arrow-in-down"></i> Abrir Caja
            </button>
        </div>
    </div>

    <!-- Estadísticas del Día -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Ventas del Día</h6>
                            <h3 class="mb-0">Bs. 0.00</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-graph-up text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Efectivo</h6>
                            <h3 class="mb-0">Bs. 0.00</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-cash text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Tarjetas</h6>
                            <h3 class="mb-0">Bs. 0.00</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-credit-card text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">QR/Transferencias</h6>
                            <h3 class="mb-0">Bs. 0.00</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-qr-code text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-cash-register text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3">Control de Caja</h4>
            <p class="text-muted">
                Gestiona las operaciones de caja: apertura, cierre, ingresos y egresos.
            </p>
            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle"></i> 
                Este módulo permite llevar un control detallado de todas las transacciones de caja del restaurante.
            </div>
        </div>
    </div>
</div>
@endsection
