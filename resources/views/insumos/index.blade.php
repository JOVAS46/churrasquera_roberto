@extends('layouts.app')

@section('title', 'Gestión de Inventario')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Inventario de Insumos</h1>
        <a href="{{ route('admin.insumos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Insumo
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Stock Actual</th>
                            <th>Unidad</th>
                            <th>Stock Mínimo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($insumos as $insumo)
                            <tr>
                                <td class="fw-bold">{{ $insumo->nombre }}</td>
                                <td>{{ number_format($insumo->stock_actual, 2) }}</td>
                                <td>{{ $insumo->unidad_medida }}</td>
                                <td>{{ number_format($insumo->stock_minimo, 2) }}</td>
                                <td>
                                    @if($insumo->stock_actual <= $insumo->stock_minimo)
                                        <span class="badge bg-danger">Bajo Stock</span>
                                    @else
                                        <span class="badge bg-success">OK</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.insumos.edit', $insumo->id_insumo) }}" class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.insumos.destroy', $insumo->id_insumo) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este insumo?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay insumos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $insumos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
