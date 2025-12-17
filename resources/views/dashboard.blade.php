@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h2">¡Bienvenido, {{ $usuario->nombre_completo }}!</h1>
                <p class="text-muted">Rol: <span class="badge bg-primary">{{ $rol }}</span></p>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-2">Total Productos</h6>
                                <h3 class="mb-0">{{ $stats['total_productos'] }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="bi bi-box-seam text-primary fs-4"></i>
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
                                <h6 class="text-muted mb-2">Disponibles</h6>
                                <h3 class="mb-0">{{ $stats['productos_disponibles'] }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="bi bi-check-circle text-success fs-4"></i>
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
                                <h6 class="text-muted mb-2">Total Pedidos</h6>
                                <h3 class="mb-0">{{ $stats['total_pedidos'] }}</h3>
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
                                <h6 class="text-muted mb-2">Usuarios Activos</h6>
                                <h3 class="mb-0">{{ $stats['total_usuarios'] }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="bi bi-people text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Páginas más visitadas -->
        <div class="row">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Páginas Más Visitadas</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Página</th>
                                        <th class="text-end">Visitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($visitas as $visita)
                                        <tr>
                                            <td><code>{{ $visita->pagina }}</code></td>
                                            <td class="text-end">
                                                <span class="badge bg-primary">{{ number_format($visita->total_visitas) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">No hay datos de visitas</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información del Sistema</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-person-badge"></i> Tu Rol</span>
                                <span class="badge bg-primary rounded-pill">{{ $rol }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-envelope"></i> Email</span>
                                <span class="text-muted">{{ $usuario->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-telephone"></i> Teléfono</span>
                                <span class="text-muted">{{ $usuario->telefono ?? 'No registrado' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-calendar"></i> Fecha de Registro</span>
                                <span class="text-muted">{{ $usuario->fecha_registro->format('d/m/Y') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
