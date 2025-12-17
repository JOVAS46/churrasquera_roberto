<div class="sidebar bg-dark text-white p-3" style="width: 250px; height: 100vh; position: fixed; top: 0; left: 0;">
    <h4 class="text-center mb-4">Admin Panel</h4>
    <ul class="nav flex-column">
        @foreach ($menuItems as $item)
            @php
                // Verificar si algún hijo está activo
                $isChildActive = collect($item['children'] ?? [])->contains('active', true);
                $isActive = $item['active'] ?? false;
                $isExpanded = $isActive || $isChildActive;
            @endphp

            @if (!empty($item['children']))
                <li class="nav-item">
                    <a class="nav-link text-white d-flex justify-content-between {{ $isExpanded ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#menu-{{ \Illuminate\Support\Str::slug($item['label']) }}" role="button" aria-expanded="{{ $isExpanded ? 'true' : 'false' }}" aria-controls="menu-{{ \Illuminate\Support\Str::slug($item['label']) }}">
                        <span><i class="{{ $item['icon'] }}"></i> {{ $item['label'] }}</span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <div class="collapse {{ $isExpanded ? 'show' : '' }}" id="menu-{{ \Illuminate\Support\Str::slug($item['label']) }}">
                        <ul class="nav flex-column ms-3">
                            @foreach ($item['children'] as $child)
                                <li>
                                    <a href="{{ $child['url'] }}" class="nav-link text-white {{ $child['active'] ? 'active fw-bold' : '' }}">
                                        {{ $child['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            @else
                <li>
                    <a href="{{ $item['url'] }}" class="nav-link text-white {{ $isActive ? 'active fw-bold' : '' }}">
                        <i class="{{ $item['icon'] }}"></i> {{ $item['label'] }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
    
    <!-- Configuración de Temas y Accesibilidad -->
    <div class="mt-auto pt-3 border-top border-secondary">
        <h6 class="text-uppercase small mb-2 text-muted">Aparencia</h6>
        <div class="d-grid gap-2 mb-2">
            <button class="btn btn-sm btn-outline-light" onclick="setTheme('theme-kids')">👶 Niños</button>
            <button class="btn btn-sm btn-outline-info" onclick="setTheme('theme-youth')">🎧 Jóvenes</button>
            <button class="btn btn-sm btn-outline-secondary" onclick="setTheme('theme-adult')">👔 Adultos</button>
        </div>
        <h6 class="text-uppercase small mb-2 text-muted">Accesibilidad</h6>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-light w-50" onclick="toggleFontSize()" title="Aumentar Texto">A+</button>
            <button class="btn btn-sm btn-warning w-50" onclick="toggleContrast()" title="Alto Contraste"><i class="bi bi-circle-half"></i></button>
        </div>
    </div>
</div>