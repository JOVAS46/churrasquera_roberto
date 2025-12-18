@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-check me-2"></i>Mis Reservas</h2>
        <a href="{{ route('cliente.reservas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Nueva Reserva
        </a>
    </div>

    @if($reservas->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-4">Fecha</th>
                                <th class="py-3 px-4">Hora</th>
                                <th class="py-3 px-4">Personas</th>
                                <th class="py-3 px-4">Mesa</th>
                                <th class="py-3 px-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $reserva)
                            <tr>
                                <td class="px-4 py-3">{{ $reserva->fecha_reserva->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">{{ $reserva->hora_inicio->format('H:i') }} - {{ $reserva->hora_fin->format('H:i') }}</td>
                                <td class="px-4 py-3">{{ $reserva->numero_personas }}</td>
                                <td class="px-4 py-3">Mesa {{ $reserva->mesa->numero_mesa ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    @if($reserva->estado == 'pendiente')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @elseif($reserva->estado == 'confirmada')
                                        <span class="badge bg-success">Confirmada</span>
                                    @elseif($reserva->estado == 'cancelada')
                                        <span class="badge bg-danger">Cancelada</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $reserva->estado }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <div class="text-muted mb-3">
                    <i class="bi bi-calendar-x display-1"></i>
                </div>
                <h3>No tienes reservas aún</h3>
                <p class="text-muted">¡Reserva una mesa y disfruta de la mejor experiencia gastronómica!</p>
                <a href="{{ route('cliente.reservas.create') }}" class="btn btn-primary mt-3">Hacer mi primera reserva</a>
            </div>
        </div>
    @endif
</div>
@endsection
