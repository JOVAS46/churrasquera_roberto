@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2"><i class="bi bi-pencil-square"></i> Editar Producto</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
                    <li class="breadcrumb-item active">Editar: {{ $producto->nombre }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('productos.update', $producto->id_producto) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre del Producto *</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
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
                                                {{ old('id_categoria', $producto->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
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
                                      id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="precio" class="form-label">Precio (Bs.) *</label>
                                <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" 
                                       id="precio" name="precio" value="{{ old('precio', $producto->precio) }}" required min="0">
                                @error('precio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="tiempo_preparacion" class="form-label">Tiempo Prep. (min)</label>
                                <input type="number" class="form-control @error('tiempo_preparacion') is-invalid @enderror" 
                                       id="tiempo_preparacion" name="tiempo_preparacion" value="{{ old('tiempo_preparacion', $producto->tiempo_preparacion) }}" min="0">
                                @error('tiempo_preparacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="disponible" class="form-label">Estado</label>
                                <select class="form-select @error('disponible') is-invalid @enderror" 
                                        id="disponible" name="disponible">
                                    <option value="1" {{ old('disponible', $producto->disponible) == 1 ? 'selected' : '' }}>Disponible</option>
                                    <option value="0" {{ old('disponible', $producto->disponible) == 0 ? 'selected' : '' }}>No disponible</option>
                                </select>
                                @error('disponible')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen del Producto</label>
                            @if($producto->imagen)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen actual" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                                   id="imagen" name="imagen" accept="image/*">
                            <small class="text-muted">Dejar vacío para mantener la imagen actual</small>
                            @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Actualizar Producto
                            </button>
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-basket"></i> Ingredientes y Receta</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Define qué insumos se descuentan del inventario al preparar este producto.
                    </p>
                    <a href="{{ route('admin.recetas.edit', $producto->id_producto) }}" class="btn btn-outline-success w-100">
                        <i class="bi bi-list-check"></i> Gestionar Receta
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-3">Imagen Actual</h6>
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen actual" class="img-fluid rounded mb-2">
                    @else
                        <div class="text-center py-4 bg-light rounded">
                            <i class="bi bi-image text-muted display-4"></i>
                            <p class="small text-muted mt-2">Sin imagen</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
