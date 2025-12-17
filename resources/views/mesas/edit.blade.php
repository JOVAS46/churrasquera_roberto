@extends('layouts.app')

@section('title', 'Editar Mesa')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2"><i class="bi bi-pencil"></i> Editar Mesa {{ $mesa->numero_mesa }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('mesas.index') }}">Mesas</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('mesas.update', $mesa->id_mesa) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="numero_mesa" class="form-label">
                                <i class="bi bi-hash"></i> Número de Mesa *
                            </label>
                            <input type="number" 
                                   class="form-control @error('numero_mesa') is-invalid @enderror" 
                                   id="numero_mesa" 
                                   name="numero_mesa" 
                                   value="{{ old('numero_mesa', $mesa->numero_mesa) }}" 
                                   required
                                   min="1">
                            @error('numero_mesa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="capacidad" class="form-label">
                                <i class="bi bi-people"></i> Capacidad (personas) *
                            </label>
                            <input type="number" 
                                   class="form-control @error('capacidad') is-invalid @enderror" 
                                   id="capacidad" 
                                   name="capacidad" 
                                   value="{{ old('capacidad', $mesa->capacidad) }}" 
                                   required
                                   min="1"
                                   max="20">
                            @error('capacidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">
                                <i class="bi bi-circle-fill"></i> Estado *
                            </label>
                            <select class="form-select @error('estado') is-invalid @enderror" 
                                    id="estado" 
                                    name="estado" 
                                    required>
                                <option value="disponible" {{ old('estado', $mesa->estado) == 'disponible' ? 'selected' : '' }}>
                                    Disponible
                                </option>
                                <option value="ocupada" {{ old('estado', $mesa->estado) == 'ocupada' ? 'selected' : '' }}>
                                    Ocupada
                                </option>
                                <option value="reservada" {{ old('estado', $mesa->estado) == 'reservada' ? 'selected' : '' }}>
                                    Reservada
                                </option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Actualizar Mesa
                            </button>
                            <a href="{{ route('mesas.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="button" 
                                    class="btn btn-danger ms-auto" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar la Mesa {{ $mesa->numero_mesa }}?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('mesas.destroy', $mesa->id_mesa) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
