@extends('layouts.app')

@section('title', 'Editar Pedido')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2"><i class="bi bi-pencil"></i> Editar Pedido #{{ $pedido->id_pedido }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('pedidos.index') }}">Pedidos</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('pedidos.update', $pedido->id_pedido) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado del Pedido *</label>
                            <select class="form-select @error('estado') is-invalid @enderror" 
                                    id="estado" name="estado" required>
                                <option value="pendiente" {{ $pedido->estado == 'pendiente' ? 'selected' : '' }}>
                                    Pendiente
                                </option>
                                <option value="en_preparacion" {{ $pedido->estado == 'en_preparacion' ? 'selected' : '' }}>
                                    En Preparación
                                </option>
                                <option value="listo" {{ $pedido->estado == 'listo' ? 'selected' : '' }}>
                                    Listo
                                </option>
                                <option value="entregado" {{ $pedido->estado == 'entregado' ? 'selected' : '' }}>
                                    Entregado
                                </option>
                                <option value="cancelado" {{ $pedido->estado == 'cancelado' ? 'selected' : '' }}>
                                    Cancelado
                                </option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                                      id="observaciones" name="observaciones" rows="3">{{ old('observaciones', $pedido->observaciones) }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Actualizar Pedido
                            </button>
                            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Información del Pedido</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-table"></i> Mesa</span>
                            <strong>{{ $pedido->mesa->numero_mesa }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-person"></i> Mesero</span>
                            <strong>{{ $pedido->mesero->nombre_completo }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-calendar"></i> Fecha</span>
                            <strong>{{ $pedido->fecha_pedido->format('d/m/Y H:i') }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><i class="bi bi-cash"></i> Total</span>
                            <strong>Bs. {{ number_format($pedido->total, 2) }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
