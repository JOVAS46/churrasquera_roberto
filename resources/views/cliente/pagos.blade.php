@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="mb-4"><i class="bi bi-wallet2 me-2"></i>Mis Pagos</h2>
            
            @if(count($pedidos) > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="py-3 px-4">Pedido #</th>
                                        <th scope="col" class="py-3 px-4">Fecha</th>
                                        <th scope="col" class="py-3 px-4">Total</th>
                                        <th scope="col" class="py-3 px-4">Estado</th>
                                        <th scope="col" class="py-3 px-4 text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservasPendientes as $reserva)
                                    <tr>
                                        <td class="px-4 py-3">Reserva #{{ $reserva->id_reserva }}</td>
                                        <td class="px-4 py-3">{{ $reserva->fecha_reserva->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 fw-bold">Bs. 50.00 (Depósito)</td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-warning text-dark">{{ ucfirst($reserva->estado) }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <form action="{{ route('cliente.pagos.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="reserva_id" value="{{ $reserva->id_reserva }}">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-circle me-1"></i>Pagar Reserva
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                    <div>
                        No tienes reservas pendientes de pago en este momento.
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('cliente.dashboard') }}" class="btn btn-outline-primary">Volver al Dashboard</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
