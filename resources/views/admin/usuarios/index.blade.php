@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h2"><i class="bi bi-people"></i> Gestión de Usuarios</h1>
            <p class="text-muted">Administra los usuarios del sistema</p>
        </div>
        <div class="col-md-6 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario">
                <i class="bi bi-plus-circle"></i> Nuevo Usuario
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h4 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Error de Validación</h4>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>
                                <td><strong>#{{ $usuario->id_usuario }}</strong></td>
                                <td>{{ $usuario->nombre_completo }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ $usuario->telefono ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $usuario->role->nombre_rol }}</span>
                                </td>
                                <td>
                                    @if($usuario->estado)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-primary" 
                                                type="button"
                                                onclick="editarUsuario(this)"
                                                data-url="{{ route('admin.usuarios.actualizar', $usuario->id_usuario) }}"
                                                data-nombre="{{ $usuario->nombre }}"
                                                data-apellido="{{ $usuario->apellido }}"
                                                data-email="{{ $usuario->email }}"
                                                data-telefono="{{ $usuario->telefono ?? '' }}"
                                                data-rol="{{ $usuario->role->id_rol ?? $usuario->id_rol }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.usuarios.cambiar-estado', $usuario->id_usuario) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-{{ $usuario->estado ? 'warning' : 'success' }}">
                                                <i class="bi bi-{{ $usuario->estado ? 'pause' : 'play' }}-circle"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.usuarios.eliminar', $usuario->id_usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No hay usuarios registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $usuarios->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Modal Crear Usuario --}}
<div class="modal fade" id="modalCrearUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.usuarios.crear') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus"></i> Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Apellido *</label>
                        <input type="text" name="apellido" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rol *</label>
                        <select name="id_rol" class="form-select" required>
                            <option value="">Seleccionar rol...</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id_rol }}">{{ $rol->nombre_rol }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Editar Usuario --}}
<div class="modal fade" id="modalEditarUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditarUsuario" action="#" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil"></i> Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre *</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Apellido *</label>
                        <input type="text" name="apellido" id="edit_apellido" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="text" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" id="edit_telefono" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nueva Contraseña (dejar vacío para no cambiar)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rol *</label>
                        <select name="id_rol" id="edit_id_rol" class="form-select" required>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id_rol }}">{{ $rol->nombre_rol }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Definir la función directamente en el scope global, fuera del DOMContentLoaded por si acaso
    window.editarUsuario = function(btn) {
        try {
            // Obtener datos
            let url = btn.dataset.url;
            let nombre = btn.dataset.nombre;
            let apellido = btn.dataset.apellido;
            let email = btn.dataset.email;
            let telefono = btn.dataset.telefono;
            let idRol = btn.dataset.rol;

            console.log('Datos recibidos:', { url, nombre });

            if (!url) {
                alert('Error crítico: No hay URL de actualización');
                return;
            }

            // Obtener referencia al formulario
            let form = document.getElementById('formEditarUsuario');
            if (!form) {
                alert('Error: No se encuentra el formulario de edición');
                return;
            }

            // Actualizar action
            form.action = url;
            console.log('Action actualizado a:', form.action);

            // Llenar campos
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_apellido').value = apellido;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_telefono').value = telefono;
            document.getElementById('edit_id_rol').value = idRol;

            // ABRIR EL MODAL MANUALMENTE
            let modalEl = document.getElementById('modalEditarUsuario');
            
            // Verificar si bootstrap está disponible
            if (typeof bootstrap === 'undefined') {
                alert('Error: Bootstrap no está cargado correctamente');
                return;
            }

            let modal = bootstrap.Modal.getInstance(modalEl);
            
            if (!modal) {
                modal = new bootstrap.Modal(modalEl);
            }
            modal.show();

        } catch (e) {
            console.error(e);
            alert('Error al abrir editor: ' + e.message);
        }
    };
</script>
@endpush

