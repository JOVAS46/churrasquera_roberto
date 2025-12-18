@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestionar Reservas</h1>
        <div>
             <form action="{{ route('reservas.index') }}" method="GET" class="d-inline-flex">
                <input type="date" name="fecha" class="form-control me-2" value="{{ request('fecha') }}">
                <select name="estado" class="form-select me-2">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                </select>
                <button type="submit" class="btn btn-primary"><i class="bi bi-filter"></i> Filtrar</button>
             </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="reservasTable" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Mesa</th>
                            <th>Pax</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->id_reserva }}</td>
                            <td>{{ $reserva->cliente->nombre_completo ?? 'N/A' }} <br> <small class="text-muted">{{ $reserva->cliente->email ?? '' }}</small></td>
                            <td>{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') }}</td>
                            <td>
                                @if($reserva->mesa)
                                    Mesa {{ $reserva->mesa->numero_mesa }}
                                @else
                                    <span class="text-danger">Sin Mesa</span>
                                @endif
                            </td>
                            <td>{{ $reserva->numero_personas }}</td>
                            <td>
                                <span class="badge bg-{{ $reserva->estado == 'confirmada' ? 'success' : ($reserva->estado == 'pendiente' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($reserva->estado) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if($reserva->estado != 'cancelada' && $reserva->estado != 'completada')
                                    <form action="{{ route('reservas.update', $reserva->id_reserva) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="estado" value="cancelada">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Cancelar esta reserva?')">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if($reserva->estado == 'pendiente')
                                    <form action="{{ route('reservas.update', $reserva->id_reserva) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="estado" value="confirmada">
                                        <button type="submit" class="btn btn-sm btn-success" title="Confirmar manual">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No se encontraron reservas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $reservas->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
