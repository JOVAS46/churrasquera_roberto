@extends('layouts.app')

@section('title', 'Nuevo Insumo')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Registrar Nuevo Insumo</h6>
        </div>
        <div class="card-body">
        </div>
        <div class="card-body">
            <form action="{{ route('admin.insumos.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label">Nombre del Insumo</label>
                    <input type="text" name="nombre" class="form-control" required placeholder="Ej: Carne de Res, Pan, Tomate">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unidad de Medida</label>
                        <select name="unidad_medida" class="form-select">
                            <option value="unidad">Unidad (pieza)</option>
                            <option value="kg">Kilogramos (kg)</option>
                            <option value="gr">Gramos (gr)</option>
                            <option value="litro">Litros (l)</option>
                            <option value="paquete">Paquete</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Inicial</label>
                        <input type="number" step="0.01" name="stock_actual" class="form-control" required value="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Mínimo (Alerta)</label>
                        <input type="number" step="0.01" name="stock_minimo" class="form-control" required value="5">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción (Opcional)</label>
                    <textarea name="descripcion" class="form-control" rows="2"></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.insumos.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar Insumo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
