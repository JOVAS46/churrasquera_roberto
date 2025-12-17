@extends('layouts.app')

@section('title', 'Pedidos en Cocina')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2"><i class="bi bi-fire"></i> Pedidos en Cocina</h1>
            <p class="text-muted">Gestión de pedidos en tiempo real</p>
        </div>
    </div>

    <div class="row g-3">
        {{-- Pendientes --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-warning border-4">
                <div class="card-header bg-warning bg-opacity-10">
                    <h5 class="mb-0"><i class="bi bi-clock"></i> Pendientes ({{ $pendientes->count() }})</h5>
                </div>
                <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
                    @forelse($pendientes as $pedido)
                        <div class="card mb-3 border-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">Pedido #{{ $pedido->id_pedido }}</h6>
                                    <span class="badge bg-warning">Mesa {{ $pedido->mesa->numero_mesa }}</span>
                                </div>
                                <small class="text-muted d-block mb-2">{{ $pedido->fecha_pedido->diffForHumans() }}</small>
                                
                                <div class="list-group list-group-flush">
                                    @foreach($pedido->detalles as $detalle)
                                        <div class="list-group-item px-0 py-1">
                                            <strong>{{ $detalle->cantidad }}x</strong> {{ $detalle->producto->nombre }}
                                            @if($detalle->observaciones)
                                                <br><small class="text-muted">{{ $detalle->observaciones }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <form action="{{ route('cocina.cambiar-estado', $pedido->id_pedido) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="estado" value="en_preparacion">
                                    <button type="submit" class="btn btn-sm btn-info w-100">
                                        <i class="bi bi-arrow-right"></i> Iniciar Preparación
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No hay pedidos pendientes</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- En Preparación --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-info border-4">
                <div class="card-header bg-info bg-opacity-10">
                    <h5 class="mb-0"><i class="bi bi-fire"></i> En Preparación ({{ $enPreparacion->count() }})</h5>
                </div>
                <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
                    @forelse($enPreparacion as $pedido)
                        <div class="card mb-3 border-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">Pedido #{{ $pedido->id_pedido }}</h6>
                                    <span class="badge bg-info">Mesa {{ $pedido->mesa->numero_mesa }}</span>
                                </div>
                                <small class="text-muted d-block mb-2">{{ $pedido->fecha_pedido->diffForHumans() }}</small>
                                
                                <div class="list-group list-group-flush">
                                    @foreach($pedido->detalles as $detalle)
                                        <div class="list-group-item px-0 py-1">
                                            <strong>{{ $detalle->cantidad }}x</strong> {{ $detalle->producto->nombre }}
                                            @if($detalle->observaciones)
                                                <br><small class="text-muted">{{ $detalle->observaciones }}</small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <form action="{{ route('cocina.cambiar-estado', $pedido->id_pedido) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="estado" value="listo">
                                    <button type="submit" class="btn btn-sm btn-success w-100">
                                        <i class="bi bi-check-circle"></i> Marcar como Listo
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No hay pedidos en preparación</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Listos --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-success border-4">
                <div class="card-header bg-success bg-opacity-10">
                    <h5 class="mb-0"><i class="bi bi-check-circle"></i> Listos ({{ $listos->count() }})</h5>
                </div>
                <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
                    @forelse($listos as $pedido)
                        <div class="card mb-3 border-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">Pedido #{{ $pedido->id_pedido }}</h6>
                                    <span class="badge bg-success">Mesa {{ $pedido->mesa->numero_mesa }}</span>
                                </div>
                                <small class="text-muted d-block mb-2">{{ $pedido->fecha_pedido->diffForHumans() }}</small>
                                
                                <div class="list-group list-group-flush">
                                    @foreach($pedido->detalles as $detalle)
                                        <div class="list-group-item px-0 py-1">
                                            <strong>{{ $detalle->cantidad }}x</strong> {{ $detalle->producto->nombre }}
                                        </div>
                                    @endforeach
                                </div>

                                <div class="alert alert-success mt-2 mb-0 py-2">
                                    <i class="bi bi-check-circle"></i> Listo para servir
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No hay pedidos listos</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh cada 30 segundos
setTimeout(function() {
    location.reload();
}, 30000);
</script>
@endpush
