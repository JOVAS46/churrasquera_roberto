@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2"><i class="bi bi-box-seam"></i> Catálogo de Productos</h1>
            <p class="text-muted">Gestiona el menú del restaurante</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('productos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nuevo Producto
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('productos.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="buscar" class="form-control" 
                           placeholder="Nombre o descripción..." 
                           value="{{ request('buscar') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Categoría</label>
                    <select name="categoria" class="form-select">
                        <option value="">Todas</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id_categoria }}" 
                                    {{ request('categoria') == $cat->id_categoria ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="disponible" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" {{ request('disponible') === '1' ? 'selected' : '' }}>Disponibles</option>
                        <option value="0" {{ request('disponible') === '0' ? 'selected' : '' }}>No disponibles</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Productos -->
    <div class="row g-4">
        @forelse($productos as $producto)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" class="card-img-top" alt="{{ $producto->nombre }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $producto->nombre }}</h5>
                            <span class="badge {{ $producto->disponible ? 'bg-success' : 'bg-secondary' }}">
                                {{ $producto->disponible ? 'Disponible' : 'No disponible' }}
                            </span>
                        </div>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-tag"></i> {{ $producto->categoria->nombre }}
                        </p>
                        <p class="card-text text-truncate">{{ $producto->descripcion }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="text-primary mb-0">Bs. {{ number_format($producto->precio, 2) }}</h4>
                            <div class="btn-group">
                                <a href="{{ route('productos.show', $producto->id_producto) }}" 
                                   class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('productos.edit', $producto->id_producto) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Editar Producto">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.recetas.edit', $producto->id_producto) }}" 
                                   class="btn btn-sm btn-outline-success" title="Gestionar Receta">
                                    <i class="bi bi-list-check"></i>
                                </a>
                            </div>
                        </div>
                        @if($producto->tiempo_preparacion)
                            <small class="text-muted">
                                <i class="bi bi-clock"></i> {{ $producto->tiempo_preparacion }} min
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No se encontraron productos.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $productos->links() }}
    </div>
</div>
@endsection
