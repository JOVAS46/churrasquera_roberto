@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Listado
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0">Información de Venta #{{ $venta->id_venta }}</h4>
                </div>
                <div class="card-body">
                    <p><strong>Fecha:</strong> {{ $venta->fecha_venta->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Registrado por:</strong> {{ $venta->usuario->nombre_completo ?? 'N/A' }}</p>
                    <p><strong>Cliente:</strong> {{ $venta->cliente->nombre_completo ?? 'Cliente General' }}</p>
                    <p><strong>Estado:</strong> 
                        @if($venta->estado == 'completada')
                            <span class="badge bg-success">Completada</span>
                        @else
                            <span class="badge bg-warning text-dark">Pendiente de Pago</span>
                        @endif
                    </p>
                    <hr>
                    <h3 class="text-end text-primary mb-3">Total: Bs. {{ number_format($venta->total, 2) }}</h3>

                    <!-- Sección de Pago QR -->
                    @if($venta->estado != 'completada')
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <h5 class="card-title text-muted mb-3"><i class="fas fa-qrcode me-2"></i>Pago con QR</h5>
                                
                                @if(isset($pago) && $pago->qr_image)
                                    <div class="mb-3">
                                        <img src="data:image/png;base64,{{ $pago->qr_image }}" alt="QR Pago" class="img-fluid border p-2 bg-white rounded" style="max-width: 200px;">
                                        <p class="small text-muted mt-2">Escanee para pagar</p>
                                    </div>
                                    <form action="{{ route('ventas.verificarEstado', $venta->id_venta) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-sync-alt me-2"></i>Verificar Pago
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('ventas.generarQR', $venta->id_venta) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-qrcode me-2"></i>Generar QR
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @else
                         <div class="alert alert-success text-center mb-0">
                            <i class="fas fa-check-circle me-2"></i>Venta Pagada
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Detalle de Productos</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Precio Unit.</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($venta->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->producto->nombre ?? 'Producto Eliminado' }}</td>
                                    <td class="text-center">{{ $detalle->cantidad }}</td>
                                    <td class="text-end">Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td class="text-end fw-bold">Bs. {{ number_format($detalle->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="3" class="text-end fw-bold">TOTAL VENTA:</td>
                                    <td class="text-end fw-bold fs-5">Bs. {{ number_format($venta->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
