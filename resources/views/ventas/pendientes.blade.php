@extends('layouts.app')

@section('title', 'Cuentas por Cobrar')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Mesas / Pedidos por Cobrar</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($pedidos->isEmpty())
        <div class="alert alert-info">
            No hay pedidos pendientes de cobro.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($pedidos as $pedido)
                <div class="col">
                    <div class="card h-100 shadow-sm border-left-primary {{ $pedido->estado == 'entregado' ? 'border-success' : 'border-warning' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title font-weight-bold text-primary">Mesa {{ $pedido->mesa->numero_mesa ?? '?' }}</h5>
                                <span class="badge {{ $pedido->estado == 'entregado' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                                </span>
                            </div>
                            
                            <p class="card-text mb-1">
                                <i class="bi bi-person"></i> Mesero: {{ $pedido->mesero->nombre ?? 'N/A' }}
                            </p>
                            <p class="card-text mb-1">
                                <i class="bi bi-clock"></i> {{ $pedido->fecha_pedido->format('H:i') }}
                            </p>
                            <h4 class="mt-3 text-center font-weight-bold">{{ number_format($pedido->total, 2) }} Bs.</h4>
                            
                            <hr>
                            
                            <form action="{{ route('ventas.procesar', $pedido->id_pedido) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 btn-lg">
                                    <i class="bi bi-cash-coin"></i> Cobrar Ahora
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
