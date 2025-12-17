@extends('layouts.app')

@push('styles')
<style>
    .product-card {
        cursor: pointer;
        transition: transform 0.2s;
        height: 100%;
    }
    .product-card:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .scrollable-products {
        max-height: 70vh;
        overflow-y: auto;
    }
    .cart-summary {
        height: 70vh;
        display: flex;
        flex-direction: column;
    }
    .cart-items {
        flex-grow: 1;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Panel Izquierdo: Catálogo de Productos -->
        <div class="col-md-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary"><i class="fas fa-utensils me-2"></i>Catálogo</h4>
                        <div class="input-group w-50">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchProduct" class="form-control border-start-0 bg-light" placeholder="Buscar producto...">
                        </div>
                    </div>
                </div>
                <div class="card-body bg-light">
                    <div class="row row-cols-1 row-cols-md-3 g-3 scrollable-products" id="productsContainer">
                        @foreach($productos as $producto)
                        <div class="col product-item" data-name="{{ strtolower($producto->nombre) }}">
                            <div class="card product-card border-0 shadow-sm h-100" onclick="addToCart({{ $producto->id_producto }}, '{{ $producto->nombre }}', {{ $producto->precio }})">
                                <div class="card-body text-center p-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex p-3 mb-2">
                                        <i class="fas fa-box-open text-primary fs-4"></i>
                                    </div>
                                    <h6 class="card-title fw-bold text-dark mb-1">{{ $producto->nombre }}</h6>
                                    <p class="card-text text-muted small">{{ Str::limit($producto->descripcion, 30) }}</p>
                                    <h5 class="text-primary fw-bold mt-2">Bs. {{ number_format($producto->precio, 2) }}</h5>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Derecho: Resumen de Venta -->
        <div class="col-md-5">
            <div class="card shadow h-100 border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Nueva Venta</h4>
                </div>
                <div class="card-body p-0 cart-summary">
                    <!-- Lista de Items -->
                    <div class="cart-items p-3">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center" width="80">Cant.</th>
                                    <th class="text-end" width="100">Subtotal</th>
                                    <th width="40"></th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <!-- Items agregados dinámicamente -->
                                <tr id="emptyCartMessage">
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-basket-shopping fa-3x mb-3 text-secondary opacity-25"></i>
                                        <p>Seleccione productos para agregar a la venta</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totales y Botones -->
                    <div class="p-4 bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-muted mb-0">Total a Pagar:</h5>
                            <h2 class="text-primary fw-bold mb-0" id="cartTotal">Bs. 0.00</h2>
                        </div>
                        
                        <form action="{{ route('ventas.store') }}" method="POST" id="saleForm">
                            @csrf
                            <div id="formInputs"></div> <!-- Inputs hidden dinámicos -->
                            
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success btn-lg py-3 rounded-pill shadow-sm fw-bold" onclick="submitSale()" id="btnProcess" disabled>
                                    <i class="fas fa-check-circle me-2"></i>PROCESAR VENTA
                                </button>
                                <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary rounded-pill">
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let cart = [];

    // Función para agregar al carrito
    function addToCart(id, name, price) {
        const existingItem = cart.find(item => item.id === id);

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: id,
                name: name,
                price: parseFloat(price),
                quantity: 1
            });
        }
        renderCart();
    }

    // Función para aumentar cantidad
    function increaseQty(id) {
        const item = cart.find(item => item.id === id);
        if (item) {
            item.quantity += 1;
            renderCart();
        }
    }

    // Función para disminuir cantidad
    function decreaseQty(id) {
        const item = cart.find(item => item.id === id);
        if (item) {
            item.quantity -= 1;
            if (item.quantity <= 0) {
                removeFromCart(id);
            } else {
                renderCart();
            }
        }
    }

    // Función para eliminar item
    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        renderCart();
    }

    // Renderizar carrito
    function renderCart() {
        const tbody = document.getElementById('cartTableBody');
        const emptyMessage = document.getElementById('emptyCartMessage');
        const totalEl = document.getElementById('cartTotal');
        const btnProcess = document.getElementById('btnProcess');
        let total = 0;

        tbody.innerHTML = '';

        if (cart.length === 0) {
            tbody.appendChild(emptyMessage);
            totalEl.innerText = 'Bs. 0.00';
            btnProcess.disabled = true;
            return;
        }

        cart.forEach(item => {
            const subtotal = item.price * item.quantity;
            total += subtotal;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="fw-bold">${item.name}</div>
                    <div class="small text-muted">Bs. ${item.price.toFixed(2)}</div>
                </td>
                <td>
                    <div class="input-group input-group-sm flex-nowrap">
                        <button class="btn btn-outline-secondary" onclick="decreaseQty(${item.id})">-</button>
                        <input type="text" class="form-control text-center px-1" value="${item.quantity}" readonly style="min-width: 30px;">
                        <button class="btn btn-outline-secondary" onclick="increaseQty(${item.id})">+</button>
                    </div>
                </td>
                <td class="text-end fw-bold">Bs. ${subtotal.toFixed(2)}</td>
                <td class="text-end">
                    <button class="btn btn-sm text-danger" onclick="removeFromCart(${item.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        totalEl.innerText = 'Bs. ' + total.toFixed(2);
        btnProcess.disabled = false;
    }

    // Filtrar productos
    document.getElementById('searchProduct').addEventListener('keyup', function(e) {
        const term = e.target.value.toLowerCase();
        const items = document.querySelectorAll('.product-item');

        items.forEach(item => {
            const name = item.getAttribute('data-name');
            if (name.includes(term)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Enviar venta
    function submitSale() {
        if (cart.length === 0) return;

        const formInputs = document.getElementById('formInputs');
        formInputs.innerHTML = '';

        cart.forEach((item, index) => {
            // Producto ID
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = `productos[${index}][id_producto]`;
            inputId.value = item.id;
            formInputs.appendChild(inputId);

            // Cantidad
            const inputQty = document.createElement('input');
            inputQty.type = 'hidden';
            inputQty.name = `productos[${index}][cantidad]`;
            inputQty.value = item.quantity;
            formInputs.appendChild(inputQty);
        });

        document.getElementById('saleForm').submit();
    }
</script>
@endpush
