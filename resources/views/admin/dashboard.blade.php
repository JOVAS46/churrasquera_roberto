@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2"><i class="bi bi-shield-check"></i> Panel de Administración</h1>
            <p class="text-muted">Gestión completa del sistema</p>
        </div>
    </div>

    {{-- Estadísticas --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Usuarios Totales</h6>
                            <h3 class="mb-0">{{ $stats['total_usuarios'] }}</h3>
                            <small class="text-success"><i class="bi bi-check-circle"></i> {{ $stats['usuarios_activos'] }} activos</small>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people text-primary fs-4"></i>
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
                            <h6 class="text-muted mb-2">Pedidos Hoy</h6>
                            <h3 class="mb-0">{{ $stats['pedidos_hoy'] }}</h3>
                            <small class="text-muted">Total: {{ $stats['total_pedidos'] }}</small>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-cart text-warning fs-4"></i>
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
                            <h6 class="text-muted mb-2">Productos</h6>
                            <h3 class="mb-0">{{ $stats['total_productos'] }}</h3>
                            <small class="text-success"><i class="bi bi-check"></i> {{ $stats['productos_disponibles'] }} disponibles</small>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-box-seam text-success fs-4"></i>
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
                            <h6 class="text-muted mb-2">Ventas Hoy</h6>
                            <h3 class="mb-0">Bs. {{ number_format($stats['ventas_hoy'], 2) }}</h3>
                            <small class="text-muted">Mes: Bs. {{ number_format($stats['ventas_mes'], 2) }}</small>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-cash-stack text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Pedidos Recientes --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Pedidos Recientes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Mesero</th>
                                    <th>Mesa</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pedidosRecientes as $pedido)
                                    <tr>
                                        <td><strong>#{{ $pedido->id_pedido }}</strong></td>
                                        <td>{{ $pedido->cliente->nombre_completo ?? 'N/A' }}</td>
                                        <td>{{ $pedido->mesero->nombre_completo ?? 'N/A' }}</td>
                                        <td>Mesa {{ $pedido->mesa->numero_mesa ?? 'N/A' }}</td>
                                        <td>Bs. {{ number_format($pedido->total, 2) }}</td>
                                        <td>
                                            @if($pedido->estado === 'pendiente')
                                                <span class="badge bg-warning">Pendiente</span>
                                            @elseif($pedido->estado === 'en_preparacion')
                                                <span class="badge bg-info">En Preparación</span>
                                            @elseif($pedido->estado === 'listo')
                                                <span class="badge bg-success">Listo</span>
                                            @elseif($pedido->estado === 'entregado')
                                                <span class="badge bg-primary">Entregado</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($pedido->estado) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $pedido->fecha_pedido->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No hay pedidos recientes</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Productos Más Vendidos --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-trophy"></i> Productos Más Vendidos</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($productosMasVendidos as $producto)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <strong>{{ $producto->nombre }}</strong>
                                    <br>
                                    <small class="text-muted">Bs. {{ number_format($producto->precio, 2) }}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $producto->detalles_pedido_count }} ventas</span>
                            </div>
                        @empty
                            <p class="text-muted text-center">No hay datos disponibles</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Accesos Rápidos --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-lightning"></i> Accesos Rápidos</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.usuarios') }}" class="btn btn-outline-primary">
                            <i class="bi bi-people"></i> Gestionar Usuarios
                        </a>
                        <a href="{{ route('admin.reportes') }}" class="btn btn-outline-success">
                            <i class="bi bi-graph-up"></i> Ver Reportes
                        </a>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-info">
                            <i class="bi bi-box-seam"></i> Gestionar Productos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
