@extends('layouts.app')

@section('title', 'Pedidos')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2"><i class="bi bi-clipboard-check"></i> Gestión de Pedidos</h1>
            <p class="text-muted">Administra los pedidos del restaurante</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('pedidos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Pedido
            </a>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Pedidos</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-clipboard text-primary fs-4"></i>
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
                            <h6 class="text-muted mb-1">Pendientes</h6>
                            <h3 class="mb-0 text-warning">{{ $stats['pendientes'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-clock text-warning fs-4"></i>
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
                            <h6 class="text-muted mb-1">En Preparación</h6>
                            <h3 class="mb-0 text-info">{{ $stats['en_preparacion'] }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-fire text-info fs-4"></i>
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
                            <h6 class="text-muted mb-1">Completados</h6>
                            <h3 class="mb-0 text-success">{{ $stats['completados'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Pedidos -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-list"></i> Lista de Pedidos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mesa</th>
                            <th>Mesero</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidos as $pedido)
                            <tr>
                                <td><strong>#{{ $pedido->id_pedido }}</strong></td>
                                <td>
                                    <i class="bi bi-table"></i> Mesa {{ $pedido->mesa->numero_mesa }}
                                </td>
                                <td>{{ $pedido->mesero->nombre_completo }}</td>
                                <td>{{ $pedido->fecha_pedido->format('d/m/Y H:i') }}</td>
                                <td><strong>Bs. {{ number_format($pedido->total, 2) }}</strong></td>
                                <td>
                                    <span class="badge 
                                        @if($pedido->estado == 'pendiente') bg-warning
                                        @elseif($pedido->estado == 'en_preparacion') bg-info
                                        @elseif($pedido->estado == 'listo') bg-primary
                                        @elseif($pedido->estado == 'entregado') bg-success
                                        @else bg-danger
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('pedidos.show', $pedido->id_pedido) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('pedidos.edit', $pedido->id_pedido) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No hay pedidos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $pedidos->links() }}
    </div>
</div>
@endsection
