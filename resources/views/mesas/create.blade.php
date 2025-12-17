@extends('layouts.app')

@section('title', 'Crear Mesa')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2"><i class="bi bi-plus-circle"></i> Crear Nueva Mesa</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('mesas.index') }}">Mesas</a></li>
                    <li class="breadcrumb-item active">Crear</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('mesas.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="numero_mesa" class="form-label">
                                <i class="bi bi-hash"></i> Número de Mesa *
                            </label>
                            <input type="number" 
                                   class="form-control @error('numero_mesa') is-invalid @enderror" 
                                   id="numero_mesa" 
                                   name="numero_mesa" 
                                   value="{{ old('numero_mesa') }}" 
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
                                   value="{{ old('capacidad', 4) }}" 
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
                                <option value="disponible" {{ old('estado') == 'disponible' ? 'selected' : '' }}>
                                    Disponible
                                </option>
                                <option value="ocupada" {{ old('estado') == 'ocupada' ? 'selected' : '' }}>
                                    Ocupada
                                </option>
                                <option value="reservada" {{ old('estado') == 'reservada' ? 'selected' : '' }}>
                                    Reservada
                                </option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Mesa
                            </button>
                            <a href="{{ route('mesas.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
