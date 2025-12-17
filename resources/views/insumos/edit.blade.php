@extends('layouts.app')

@section('title', 'Editar Insumo')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Editar Insumo / Ajustar Stock</h6>
        </div>
        <div class="card-body">
        <div class="card-body">
            <form action="{{ route('admin.insumos.update', $insumo->id_insumo) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Nombre del Insumo</label>
                    <input type="text" name="nombre" class="form-control" required value="{{ $insumo->nombre }}">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unidad de Medida</label>
                        <select name="unidad_medida" class="form-select">
                            <option value="unidad" {{ $insumo->unidad_medida == 'unidad' ? 'selected' : '' }}>Unidad (pieza)</option>
                            <option value="kg" {{ $insumo->unidad_medida == 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                            <option value="gr" {{ $insumo->unidad_medida == 'gr' ? 'selected' : '' }}>Gramos (gr)</option>
                            <option value="litro" {{ $insumo->unidad_medida == 'litro' ? 'selected' : '' }}>Litros (l)</option>
                            <option value="paquete" {{ $insumo->unidad_medida == 'paquete' ? 'selected' : '' }}>Paquete</option>
                        </select>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Use este formulario para registrar compras o pérdidas (Ajuste Manual).
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Actual</label>
                        <input type="number" step="0.01" name="stock_actual" class="form-control fw-bold" required value="{{ $insumo->stock_actual }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Mínimo (Alerta)</label>
                        <input type="number" step="0.01" name="stock_minimo" class="form-control" required value="{{ $insumo->stock_minimo }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción (Opcional)</label>
                    <textarea name="descripcion" class="form-control" rows="2">{{ $insumo->descripcion }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.insumos.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar Insumo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
