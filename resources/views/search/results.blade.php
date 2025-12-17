@extends('layouts.app')

@section('title', 'Resultados de Búsqueda')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Resultados para: "{{ $query }}"</h2>

    @if($productos->isEmpty() && $categorias->isEmpty() && $modulos->isEmpty() && $pedidos->isEmpty())
        <div class="alert alert-info">
            No se encontraron resultados para "{{ $query }}". Intenta con otro término.
        </div>
    @else
        
        <!-- Módulos del Sistema -->
        @if(!$modulos->isEmpty())
            <h4 class="mt-4 text-primary"><i class="bi bi-compass"></i> Secciones del Sistema</h4>
            <div class="list-group mb-4">
                @foreach($modulos as $modulo)
                    <a href="{{ $modulo['ruta'] }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi {{ $modulo['icono'] }} fs-4 me-3 text-secondary"></i>
                        <div>
                            <h5 class="mb-0">{{ $modulo['nombre'] }}</h5>
                            <small class="text-muted">Ir a la sección de {{ strtolower($modulo['nombre']) }}</small>
                        </div>
                        <i class="bi bi-arrow-right ms-auto"></i>
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Pedidos Encontrados -->
        @if(!$pedidos->isEmpty())
            <h4 class="mt-4 text-success"><i class="bi bi-receipt"></i> Pedidos / Ventas</h4>
            <div class="list-group mb-4">
                @foreach($pedidos as $pedido)
                    <a href="{{ route('ventas.show', $pedido->id_pedido) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Pedido #{{ $pedido->id_pedido }}</h5>
                            <small>{{ $pedido->fecha_pedido->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">
                            Cliente: <strong>{{ $pedido->cliente->nombre_completo ?? 'Cliente General' }}</strong> 
                            | Mesa: {{ $pedido->mesa->numero_mesa ?? 'N/A' }}
                        </p>
                        <small class="text-primary fw-bold">Total: Bs. {{ number_format($pedido->total, 2) }}</small>
                        <span class="badge bg-{{ $pedido->estado == 'pagado' ? 'success' : 'secondary' }} float-end">
                            {{ ucfirst($pedido->estado) }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
        
        <!-- Productos encontrados -->
        @if(!$productos->isEmpty())
            <h4 class="mt-4"><i class="bi bi-box-seam"></i> Productos encontrados</h4>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($productos as $producto)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $producto->nombre }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($producto->descripcion, 60) }}</p>
                                <p class="card-text fw-bold text-primary">{{ number_format($producto->precio, 2) }} Bs.</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Categorías encontradas -->
        @if(!$categorias->isEmpty())
            <h4 class="mt-5"><i class="bi bi-tags"></i> Categorías encontradas</h4>
            <ul class="list-group">
                @foreach($categorias as $categoria)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $categoria->nombre }}
                        <span class="badge bg-secondary rounded-pill">{{ $categoria->tipo }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

    @endif
    
    <div class="mt-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
    </div>
</div>
@endsection
