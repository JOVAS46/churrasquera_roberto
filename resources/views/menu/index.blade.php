@extends('layouts.app')

@section('title', 'Menú del Día')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2"><i class="bi bi-book"></i> Menú del Día</h1>
            <p class="text-muted">Explora nuestro delicioso menú</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-book text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3">Menú del Restaurante</h4>
            <p class="text-muted">
                Aquí podrás ver todos los productos disponibles organizados por categoría.
            </p>
            <a href="{{ route('productos.index') }}" class="btn btn-primary mt-3">
                <i class="bi bi-eye"></i> Ver Productos
            </a>
        </div>
    </div>
</div>
@endsection
