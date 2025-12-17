@extends('layouts.app')

@section('title', 'Gestión de Mesas')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2"><i class="bi bi-table"></i> Gestión de Mesas</h1>
            <p class="text-muted">Administra las mesas del restaurante</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('mesas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Mesa
            </a>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Mesas</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-table text-primary fs-4"></i>
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
                            <h6 class="text-muted mb-1">Disponibles</h6>
                            <h3 class="mb-0 text-success">{{ $stats['disponibles'] }}</h3>
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
                            <h6 class="text-muted mb-1">Ocupadas</h6>
                            <h3 class="mb-0 text-danger">{{ $stats['ocupadas'] }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-x-circle text-danger fs-4"></i>
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
                            <h6 class="text-muted mb-1">Reservadas</h6>
                            <h3 class="mb-0 text-warning">{{ $stats['reservadas'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-clock text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Mesas -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-list"></i> Lista de Mesas</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @forelse($mesas as $mesa)
                    <div class="col-md-3">
                        <div class="card h-100 border-0 shadow-sm
                            @if($mesa->estado == 'disponible') border-start border-success border-4
                            @elseif($mesa->estado == 'ocupada') border-start border-danger border-4
                            @else border-start border-warning border-4
                            @endif">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h4 class="mb-0">Mesa {{ $mesa->numero_mesa }}</h4>
                                        <small class="text-muted">
                                            <i class="bi bi-people"></i> {{ $mesa->capacidad }} personas
                                        </small>
                                    </div>
                                    <span class="badge 
                                        @if($mesa->estado == 'disponible') bg-success
                                        @elseif($mesa->estado == 'ocupada') bg-danger
                                        @else bg-warning
                                        @endif">
                                        {{ ucfirst($mesa->estado) }}
                                    </span>
                                </div>
                                
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('mesas.edit', $mesa->id_mesa) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    @if($mesa->estado != 'disponible')
                                        <form action="{{ route('mesas.cambiar-estado', $mesa->id_mesa) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="estado" value="disponible">
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-check"></i> Liberar
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($mesa->estado == 'disponible')
                                        <form action="{{ route('mesas.cambiar-estado', $mesa->id_mesa) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="estado" value="ocupada">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-x"></i> Ocupar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No hay mesas registradas.
                            <a href="{{ route('mesas.create') }}">Crear la primera mesa</a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
