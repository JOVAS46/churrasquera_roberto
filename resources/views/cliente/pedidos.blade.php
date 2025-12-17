@extends('layouts.app')

@section('title', 'Mis Pedidos')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2"><i class="bi bi-bag-check"></i> Mis Pedidos</h1>
            <p class="text-muted">Historial de tus pedidos</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-bag-check text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3">Historial de Pedidos</h4>
            <p class="text-muted">
                Aquí podrás ver el historial de todos tus pedidos realizados.
            </p>
            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle"></i> 
                No tienes pedidos registrados aún.
            </div>
        </div>
    </div>
</div>
@endsection
