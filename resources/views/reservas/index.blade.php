@extends('layouts.app')

@section('title', 'Reservas')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2"><i class="bi bi-calendar-check"></i> Reservas</h1>
            <p class="text-muted">Gestiona tus reservas de mesa</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-calendar-check text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3">Sistema de Reservas</h4>
            <p class="text-muted">
                Reserva tu mesa con anticipación para garantizar tu lugar.
            </p>
            <button class="btn btn-primary mt-3" disabled>
                <i class="bi bi-plus-circle"></i> Nueva Reserva
            </button>
        </div>
    </div>
</div>
@endsection
