@extends('layouts.app')

@section('title', 'Facturas')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2"><i class="bi bi-file-earmark-text"></i> Gestión de Facturas</h1>
            <p class="text-muted">Administra las facturas del restaurante</p>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary" disabled>
                <i class="bi bi-plus-circle"></i> Nueva Factura
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-file-earmark-text text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3">Módulo de Facturas</h4>
            <p class="text-muted">
                Este módulo permite generar facturas automáticamente desde los pedidos completados.
            </p>
            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle"></i> 
                Las facturas se generarán automáticamente cuando un pedido sea marcado como "Entregado" y tenga un pago asociado.
            </div>
        </div>
    </div>
</div>
@endsection
