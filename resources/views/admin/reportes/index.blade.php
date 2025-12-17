@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2"><i class="bi bi-graph-up"></i> Reportes y Estadísticas</h1>
            <p class="text-muted">Análisis de ventas y desempeño</p>
        </div>
    </div>

    {{-- Filtros y Gráfico --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-filter"></i> Filtros</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reportes') }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="{{ $fechaInicio }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" value="{{ $fechaFin }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Actualizar Reporte
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Tendencia de Ventas</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Ventas --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-table"></i> Detalle de Ventas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th class="text-end">Total Ventas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ventasPorDia as $venta)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                                        <td class="text-end">
                                            <strong>Bs. {{ number_format($venta->total, 2) }}</strong>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">No hay datos en este rango de fechas</td>
                                    </tr>
                                @endforelse
                                @if($ventasPorDia->count() > 0)
                                    <tr class="table-primary">
                                        <td><strong>TOTAL</strong></td>
                                        <td class="text-end">
                                            <strong>Bs. {{ number_format($ventasPorDia->sum('total'), 2) }}</strong>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Datos desde Laravel
        const labels = @json($ventasPorDia->pluck('fecha')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m')));
        const data = @json($ventasPorDia->pluck('total'));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventas (Bs)',
                    data: data,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#4e73df',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return 'Bs. ' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 2]
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Bs ' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

    <div class="row g-4">
        {{-- Productos Más Vendidos --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-trophy"></i> Top 10 Productos Más Vendidos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th class="text-end">Ventas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productosMasVendidos as $index => $producto)
                                    <tr>
                                        <td>
                                            @if($index === 0)
                                                <i class="bi bi-trophy-fill text-warning"></i>
                                            @elseif($index === 1)
                                                <i class="bi bi-trophy-fill text-secondary"></i>
                                            @elseif($index === 2)
                                                <i class="bi bi-trophy-fill text-danger"></i>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $producto->nombre }}</strong>
                                            <br>
                                            <small class="text-muted">Bs. {{ number_format($producto->precio, 2) }}</small>
                                        </td>
                                        <td>{{ $producto->categoria->nombre ?? 'N/A' }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-primary">{{ $producto->detalles_pedido_count }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Desempeño por Mesero --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person-badge"></i> Desempeño por Mesero</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mesero</th>
                                    <th>Email</th>
                                    <th class="text-end">Pedidos Atendidos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($desempenoMeseros as $mesero)
                                    <tr>
                                        <td>
                                            <strong>{{ $mesero->nombre_completo }}</strong>
                                        </td>
                                        <td>{{ $mesero->email }}</td>
                                        <td class="text-end">
                                            <span class="badge bg-success">{{ $mesero->pedidos_como_mesero_count }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
