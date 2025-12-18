@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Nueva Reserva</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('cliente.reservas.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required min="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="hora" class="form-label">Hora</label>
                            <input type="time" class="form-control" id="hora" name="hora" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="mesa_id" class="form-label">Mesa</label>
                            <select class="form-select" id="mesa_id" name="mesa_id" required disabled>
                                <option value="">Seleccione Fecha y Hora primero</option>
                            </select>
                            <small class="text-muted" id="mesa-help">Las mesas disponibles aparecerán aquí.</small>
                        </div>

                        <div class="mb-3">
                            <label for="personas" class="form-label">Número de Personas</label>
                            <input type="number" class="form-control" id="personas" name="personas" min="1" value="2" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas Adicionales</label>
                            <textarea class="form-control" id="notas" name="notas" rows="3" placeholder="Celebración especial, alergias, etc."></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="submit-btn" disabled>Confirmar Reserva</button>
                            <a href="{{ route('cliente.dashboard') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fechaInput = document.getElementById('fecha');
        const horaInput = document.getElementById('hora');
        const mesaSelect = document.getElementById('mesa_id');
        const submitBtn = document.getElementById('submit-btn');
        const mesaHelp = document.getElementById('mesa-help');

        function loadMesas() {
            const fecha = fechaInput.value;
            const hora = horaInput.value;

            if (fecha && hora) {
                mesaSelect.disabled = true;
                mesaSelect.innerHTML = '<option value="">Cargando mesas...</option>';
                mesaHelp.textContent = 'Buscando disponibilidad...';

                fetch(`{{ route('cliente.reservas.disponibles') }}?fecha=${fecha}&hora=${hora}`)
                    .then(response => response.json())
                    .then(data => {
                        mesaSelect.innerHTML = '<option value="">Seleccione una mesa...</option>';
                        
                        if (data.length === 0) {
                            mesaSelect.innerHTML = '<option value="">No hay mesas disponibles</option>';
                            mesaHelp.textContent = 'Intente con otra hora.';
                            mesaHelp.classList.add('text-danger');
                            submitBtn.disabled = true;
                        } else {
                            data.forEach(mesa => {
                                const option = document.createElement('option');
                                option.value = mesa.id_mesa;
                                option.textContent = `Mesa ${mesa.numero_mesa} (${mesa.ubicacion}) - Cap: ${mesa.capacidad}`;
                                mesaSelect.appendChild(option);
                            });
                            mesaSelect.disabled = false;
                            mesaHelp.textContent = `${data.length} mesas disponibles.`;
                            mesaHelp.classList.remove('text-danger');
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        mesaSelect.innerHTML = '<option value="">Error al cargar</option>';
                    });
            }
        }

        fechaInput.addEventListener('change', loadMesas);
        horaInput.addEventListener('change', loadMesas);
    });
</script>
