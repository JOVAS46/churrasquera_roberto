@extends('layouts.app')

@section('title', 'Nuevo Pedido')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h2"><i class="bi bi-plus-circle"></i> Nuevo Pedido</h1>
            <p class="text-muted">Crear un nuevo pedido para una mesa</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <form action="{{ route('pedidos.store') }}" method="POST" id="formPedido">
        @csrf
        <div class="row g-4">
            {{-- Información del Pedido --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información del Pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Mesa *</label>
                            <select name="id_mesa" class="form-select" required>
                                <option value="">Seleccionar mesa...</option>
                                @foreach($mesas as $mesa)
                                    <option value="{{ $mesa->id_mesa }}">
                                        Mesa {{ $mesa->numero_mesa }} ({{ $mesa->capacidad }} personas)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cliente (Opcional)</label>
                            <input type="text" class="form-control" placeholder="Nombre del cliente">
                            <input type="hidden" name="id_cliente">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="3" placeholder="Observaciones generales del pedido"></textarea>
                        </div>

                        <div class="alert alert-info">
                            <strong>Total: Bs. <span id="totalPedido">0.00</span></strong>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Crear Pedido
                        </button>
                    </div>
                </div>
            </div>

            {{-- Productos --}}
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-cart"></i> Productos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3" id="productosContainer">
                            @foreach($productos->groupBy('categoria.nombre') as $categoria => $productosCategoria)
                                <div class="col-12">
                                    <h6 class="text-primary border-bottom pb-2">{{ $categoria ?? 'Sin categoría' }}</h6>
                                </div>
                                @foreach($productosCategoria as $producto)
                                    <div class="col-md-6">
                                        <div class="card h-100 producto-card" data-id="{{ $producto->id_producto }}" data-precio="{{ $producto->precio }}" data-nombre="{{ $producto->nombre }}">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $producto->nombre }}</h6>
                                                <p class="card-text text-muted small">{{ $producto->descripcion }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <strong class="text-success">Bs. {{ number_format($producto->precio, 2) }}</strong>
                                                    <div class="input-group input-group-sm" style="width: 120px;">
                                                        <button type="button" class="btn btn-outline-secondary btn-minus" data-id="{{ $producto->id_producto }}">-</button>
                                                        <input type="number" class="form-control text-center cantidad-input" data-id="{{ $producto->id_producto }}" value="0" min="0">
                                                        <button type="button" class="btn btn-outline-secondary btn-plus" data-id="{{ $producto->id_producto }}">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="productosHidden"></div>
    </form>
</div>

@endsection

@push('scripts')
<script>
let productosSeleccionados = {};

// Botones + y -
document.querySelectorAll('.btn-plus').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const input = document.querySelector(`.cantidad-input[data-id="${id}"]`);
        input.value = parseInt(input.value) + 1;
        actualizarProducto(id, parseInt(input.value));
    });
});

document.querySelectorAll('.btn-minus').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const input = document.querySelector(`.cantidad-input[data-id="${id}"]`);
        if (parseInt(input.value) > 0) {
            input.value = parseInt(input.value) - 1;
            actualizarProducto(id, parseInt(input.value));
        }
    });
});

document.querySelectorAll('.cantidad-input').forEach(input => {
    input.addEventListener('change', function() {
        const id = this.dataset.id;
        actualizarProducto(id, parseInt(this.value));
    });
});

function actualizarProducto(id, cantidad) {
    const card = document.querySelector(`.producto-card[data-id="${id}"]`);
    const precio = parseFloat(card.dataset.precio);
    
    if (cantidad > 0) {
        productosSeleccionados[id] = {
            id: id,
            cantidad: cantidad,
            precio: precio
        };
    } else {
        delete productosSeleccionados[id];
    }
    
    actualizarTotal();
    actualizarInputsHidden();
}

function actualizarTotal() {
    let total = 0;
    Object.values(productosSeleccionados).forEach(prod => {
        total += prod.precio * prod.cantidad;
    });
    document.getElementById('totalPedido').textContent = total.toFixed(2);
}

function actualizarInputsHidden() {
    const container = document.getElementById('productosHidden');
    container.innerHTML = '';
    
    Object.values(productosSeleccionados).forEach((prod, index) => {
        container.innerHTML += `
            <input type="hidden" name="productos[${index}][id]" value="${prod.id}">
            <input type="hidden" name="productos[${index}][cantidad]" value="${prod.cantidad}">
        `;
    });
}

// Validar antes de enviar
document.getElementById('formPedido').addEventListener('submit', function(e) {
    if (Object.keys(productosSeleccionados).length === 0) {
        e.preventDefault();
        alert('Debe seleccionar al menos un producto');
    }
});
</script>
@endpush
