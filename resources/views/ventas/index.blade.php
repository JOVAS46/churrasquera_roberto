@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Listado de Ventas</h3>
                    <a href="{{ route('ventas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Venta
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Registrado Por</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ventas as $venta)
                                <tr>
                                    <td>{{ $venta->id_venta }}</td>
                                    <td>{{ $venta->fecha_venta->format('d/m/Y H:i') }}</td>
                                    <td>{{ $venta->usuario->nombre_completo ?? 'N/A' }}</td>
                                    <td>{{ $venta->cliente->nombre_completo ?? 'General' }}</td>
                                    <td>Bs. {{ number_format($venta->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $venta->estado == 'completada' ? 'success' : 'danger' }}">
                                            {{ ucfirst($venta->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('ventas.show', $venta->id_venta) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Ver Detalle
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $ventas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
