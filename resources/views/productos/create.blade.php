@extends('layouts.app')

@section('title', 'Crear Producto')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2"><i class="bi bi-plus-circle"></i> Crear Nuevo Producto</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
                    <li class="breadcrumb-item active">Crear</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre del Producto *</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="id_categoria" class="form-label">Categoría *</label>
                                <select class="form-select @error('id_categoria') is-invalid @enderror" 
                                        id="id_categoria" name="id_categoria" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id_categoria }}" 
                                                {{ old('id_categoria') == $categoria->id_categoria ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_categoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="precio" class="form-label">Precio (Bs.) *</label>
                                <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" 
                                       id="precio" name="precio" value="{{ old('precio') }}" required min="0">
                                @error('precio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="tiempo_preparacion" class="form-label">Tiempo Prep. (min)</label>
                                <input type="number" class="form-control @error('tiempo_preparacion') is-invalid @enderror" 
                                       id="tiempo_preparacion" name="tiempo_preparacion" value="{{ old('tiempo_preparacion') }}" min="0">
                                @error('tiempo_preparacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="disponible" class="form-label">Estado</label>
                                <select class="form-select @error('disponible') is-invalid @enderror" 
                                        id="disponible" name="disponible">
                                    <option value="1" {{ old('disponible', 1) == 1 ? 'selected' : '' }}>Disponible</option>
                                    <option value="0" {{ old('disponible') == 0 ? 'selected' : '' }}>No disponible</option>
                                </select>
                                @error('disponible')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen del Producto</label>
                            <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                                   id="imagen" name="imagen" accept="image/*">
                            @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Producto
                            </button>
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
