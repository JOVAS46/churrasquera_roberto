@extends('layouts.app')

@section('title', 'Receta: ' . $producto->nombre)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Receta: {{ $producto->nombre }}</h1>
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver a Productos
        </a>
    </div>

    <div class="row">
        <!-- Lista de Ingredientes Actuales -->
        <div class="col-md-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ingredientes Actuales</h6>
                </div>
                <div class="card-body">
                    @if($producto->insumos->isEmpty())
                        <div class="alert alert-warning">
                            Este producto no tiene ingredientes asignados (No descontará stock).
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th>Cantidad Requerida</th>
                                        <th>Unidad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($producto->insumos as $insumo)
                                        <tr>
                                            <td>{{ $insumo->nombre }}</td>
                                            <td class="font-weight-bold">{{ number_format($insumo->pivot->cantidad_requerida, 3) }}</td>
                                            <td>{{ $insumo->unidad_medida }}</td>
                                            <td>
                                                <form action="{{ route('admin.recetas.destroy', ['id' => $producto->id_producto, 'insumo' => $insumo->id_insumo]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Quitar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Formulario para Agregar Ingrediente -->
        <div class="col-md-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Agregar Ingrediente</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.recetas.store', $producto->id_producto) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Seleccionar Insumo</label>
                            <select name="id_insumo" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($insumos as $insumo)
                                    <option value="{{ $insumo->id_insumo }}">
                                        {{ $insumo->nombre }} ({{ $insumo->stock_actual }} {{ $insumo->unidad_medida }} en stock)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cantidad Requerida</label>
                            <input type="number" step="0.001" name="cantidad_requerida" class="form-control" required placeholder="Ej: 0.2 para 200gr">
                            <small class="text-muted">Ingrese la cantidad necesaria para preparar 1 unidad de este producto.</small>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-plus-lg"></i> Agregar a Receta
                        </button>
                    </form>
                </div>
            </div>

            <!-- Ayuda -->
            <div class="card shadow mb-4 border-left-info">
                <div class="card-body">
                    <p class="mb-0">
                        <strong>Nota:</strong> Cuando se confirme la preparación de un pedido, el sistema descontará automáticamente estas cantidades del stock general.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
