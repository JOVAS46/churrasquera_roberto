<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Dashboard')</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    :root {
      /* Valores por Defecto (Adultos - Professional) */
      --bg-color: #f1f5f9;
      --text-color: #334155;
      
      --sidebar-bg: #1e293b;
      --sidebar-text: #f8fafc;
      
      --navbar-bg: #ffffff;
      --navbar-border: #e2e8f0;
      
      --card-bg: #ffffff;
      --card-text: #334155;
      --card-border: #e2e8f0;
      --card-radius: 0.5rem;
      --card-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
      
      --primary-color: #0f172a; /* Slate 900 */
      --accent-color: #3b82f6; /* Blue 500 */
      
      --font-family: 'Segoe UI', system-ui, sans-serif;
      --font-size-base: 16px;
      
      --sidebar-width: 250px;
    }

    /* TEMA NIÑOS: Juguetón, Colorido, Redondeado */
    .theme-kids {
      --bg-color: #fff7ed; /* Orange 50 */
      --text-color: #5b21b6; /* Violet 800 */
      
      --sidebar-bg: #f472b6; /* Pink 400 */
      --sidebar-text: #ffffff;
      
      --navbar-bg: #fef3c7; /* Amber 100 */
      --navbar-border: #fcd34d;
      
      --card-bg: #ffffff;
      --card-text: #4c1d95;
      --card-border: #f9a8d4; /* Pink 300 */
      --card-radius: 2rem; /* Muy redondeado */
      --card-shadow: 8px 8px 0px #fdba74; /* Sombra sólida divertida */
      
      --primary-color: #db2777; /* Pink 600 */
      --accent-color: #f59e0b; /* Amber 500 */
      
      --font-family: 'Comic Sans MS', 'Chalkboard SE', sans-serif;
    }

    /* TEMA JÓVENES: Oscuro, Neón, Gamer, Cyberpunk */
    .theme-youth {
      --bg-color: #0f172a; /* Slate 900 */
      --text-color: #e2e8f0; /* Slate 200 */
      
      --sidebar-bg: #020617; /* Slate 950 */
      --sidebar-text: #22d3ee; /* Cyan 400 */
      
      --navbar-bg: #1e293b;
      --navbar-border: #22d3ee;
      
      --card-bg: #1e293b;
      --card-text: #94a3b8;
      --card-border: #22d3ee; /* Borde Neón */
      --card-radius: 0px; /* Cuadrado tech */
      --card-shadow: 0 0 15px rgba(34, 211, 238, 0.3); /* Glow */
      
      --primary-color: #22d3ee;
      --accent-color: #a855f7; /* Purple 500 */
      
      --font-family: 'Courier New', monospace; /* Tech vibe */
    }

    /* TEMA ADULTOS: Explícito (Reinicia a root por consistencia) */
    .theme-adult {
      /* Usa las variables root por defecto */
    }

    /* ACCESIBILIDAD */
    .theme-high-contrast {
      --bg-color: #000000 !important;
      --text-color: #ffffff !important;
      --sidebar-bg: #000000 !important;
      --sidebar-text: #ffff00 !important;
      --navbar-bg: #000000 !important;
      --navbar-border: #ffffff !important;
      --card-bg: #000000 !important;
      --card-text: #ffffff !important;
      --card-border: #ffffff !important;
      --primary-color: #ffff00 !important;
      --accent-color: #ffff00 !important;
      --card-shadow: none !important;
    }

    .text-large {
      font-size: 1.25rem !important;
    }

    /* APLICACIÓN DE VARIABLES A COMPONENTES */
    body {
      background-color: var(--bg-color);
      color: var(--text-color);
      font-family: var(--font-family);
      font-size: var(--font-size-base);
      transition: all 0.4s ease; /* Transición suave global */
    }
    
    /* Wrapper principal */
    .main-content {
        margin-left: var(--sidebar-width);
        transition: margin-left 0.3s;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    
    .content {
      padding: 20px;
      flex: 1;
    }

    /* Sidebar */
    .sidebar {
      background-color: var(--sidebar-bg);
      color: var(--sidebar-text);
      width: var(--sidebar-width);
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding: 1rem;
      overflow-y: auto;
      border-right: 1px solid rgba(255,255,255,0.1);
      z-index: 1040; /* Ensure it stays on top */
    }
    .sidebar .nav-link { color: var(--sidebar-text); }
    .sidebar .nav-link:hover {
      background-color: rgba(255,255,255,0.2);
    }
    .sidebar h4 { color: var(--sidebar-text); }

    /* Navbar */
    .navbar {
      background-color: var(--navbar-bg) !important;
      border-bottom: 2px solid var(--navbar-border);
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .navbar-brand, .nav-link {
        color: var(--text-color) !important;
    }

    /* Tarjetas (Cards) */
    .card {
      background-color: var(--card-bg);
      color: var(--card-text);
      border: 1px solid var(--card-border);
      border-radius: var(--card-radius);
      box-shadow: var(--card-shadow);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    
    /* Efecto hover suave en cards para thema jóvenes especialmente */
    .card:hover {
        transform: translateY(-2px);
    }

    /* Botones */
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      color: var(--bg-color) == #000000 ? #000 : #fff; /* Contraste básico */
    }
    
    .btn-primary:hover {
        opacity: 0.9;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    /* Tablas */
    .table {
      --bs-table-color: var(--text-color);
      --bs-table-bg: transparent;
      --bs-table-border-color: var(--card-border);
      
      color: var(--text-color);
      border-color: var(--card-border);
    }
    
    .table > :not(caption) > * > * {
        background-color: transparent; /* Permite ver el fondo de la card/body si es necesario, o fix */
        color: var(--text-color);
        border-bottom-color: var(--card-border);
    }

    .table thead th {
        border-bottom: 2px solid var(--accent-color);
        color: var(--primary-color);
        font-weight: bold;
    }

    /* Headings */
    h1, h2, h3, h4, h5, h6 {
        color: var(--primary-color);
    }
    
    /* Inputs de búsqueda y forms */
    .form-control {
        background-color: var(--card-bg);
        color: var(--text-color);
        border: 1px solid var(--card-border);
        border-radius: var(--card-radius);
    }
    .form-control:focus {
        background-color: var(--card-bg);
        color: var(--text-color);
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.25rem rgba(var(--accent-color), 0.25);
    }
  </style>
</head>
<body>

  @include('partials.sidebar')

  <div class="main-content">
    @include('partials.navbar')

    <div class="content">
      <!-- Flash Messages -->
      @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif
      @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      @endif

      @yield('content')
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Theme Script -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
        const body = document.body;
        
        // 1. Cargar Tema guardado
        const savedTheme = localStorage.getItem('appTheme') || 'theme-adult';
        body.classList.add(savedTheme);

        // 2. Cargar Accesibilidad
        if(localStorage.getItem('fontSize') === 'large') {
            body.classList.add('text-large');
        }
        if(localStorage.getItem('highContrast') === 'true') {
            body.classList.add('theme-high-contrast');
        }

        // 3. Auto-detectar noche si no hay preferencia explicita (opcional, o forzar lógica)
        // Si usuario eligió tema, respetamos. Si no, podríamos detectar.
        // Aquí asumimos simple: User choice > System.
    });

    function setTheme(themeName) {
        document.body.classList.remove('theme-kids', 'theme-youth', 'theme-adult');
        document.body.classList.add(themeName);
        localStorage.setItem('appTheme', themeName);
    }

    function toggleFontSize() {
        document.body.classList.toggle('text-large');
        localStorage.setItem('fontSize', document.body.classList.contains('text-large') ? 'large' : 'normal');
    }

    function toggleContrast() {
        document.body.classList.toggle('theme-high-contrast');
        localStorage.setItem('highContrast', document.body.classList.contains('theme-high-contrast'));
    }
  </script>

  @stack('scripts')
</body>
</html>