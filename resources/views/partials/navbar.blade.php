<nav class="navbar navbar-expand-lg navbar-light bg-light ps-3">
    <div class="container-fluid">
        <!-- Buscador Global -->
        <form action="{{ route('search.query') }}" method="GET" class="d-flex w-50">
            <input class="form-control me-2" type="search" name="q" placeholder="Buscar productos, categorías..." aria-label="Search" value="{{ request('q') }}">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>

        <ul class="navbar-nav ms-auto me-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ auth()->user()->name ?? 'User' }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>