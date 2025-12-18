<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;

// Redirect root to login page
Route::get('/', function () {
    return redirect()->route('login');
});

// Login/Logout routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Dashboard (accesible para todos los roles autenticados)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Global Search
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'query'])->name('search.query');
    
    // Productos (accesible para Todos los roles de staff)
    Route::resource('productos', ProductoController::class)
        ->middleware('role:Administrador,gerente,Mesero,mesero,Cocinero,cocinero,Cajero,cajero');
    
    // Mesas (accesible para Admin y Mesero)
    Route::resource('mesas', App\Http\Controllers\MesaController::class)
        ->middleware('role:Administrador,gerente,Mesero,mesero');
    Route::patch('/mesas/{mesa}/cambiar-estado', [App\Http\Controllers\MesaController::class, 'cambiarEstado'])
        ->name('mesas.cambiar-estado')
        ->middleware('role:Administrador,gerente,Mesero,mesero');
        
    // Reservas (Gestión para Admin y Mesero)
    Route::resource('reservas', App\Http\Controllers\ReservaController::class)
        ->only(['index', 'update'])
        ->middleware('role:Administrador,gerente,Mesero,mesero');
    
    // Rutas específicas por rol
    
    // Administrador
    // Administrador (permitir 'Administrador' o 'gerente')
    Route::middleware('role:Administrador,gerente')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
        Route::get('/usuarios', [App\Http\Controllers\AdminController::class, 'usuarios'])->name('usuarios');
        Route::post('/usuarios', [App\Http\Controllers\AdminController::class, 'crearUsuario'])->name('usuarios.crear');
        Route::put('/usuarios/{id}', [App\Http\Controllers\AdminController::class, 'actualizarUsuario'])->name('usuarios.actualizar');
        Route::patch('/usuarios/{id}/estado', [App\Http\Controllers\AdminController::class, 'cambiarEstadoUsuario'])->name('usuarios.cambiar-estado');
        Route::delete('/usuarios/{id}', [App\Http\Controllers\AdminController::class, 'eliminarUsuario'])->name('usuarios.eliminar');
        Route::get('/reportes', [App\Http\Controllers\AdminController::class, 'reportes'])->name('reportes');
        
        // Gestión de Insumos (Inventario)
        Route::resource('insumos', App\Http\Controllers\InsumoController::class);
        
        // Gestión de Recetas
        Route::get('/productos/{id}/receta', [App\Http\Controllers\RecetaController::class, 'edit'])->name('recetas.edit');
        Route::post('/productos/{id}/receta', [App\Http\Controllers\RecetaController::class, 'store'])->name('recetas.store');
        Route::delete('/productos/{id}/receta/{insumo}', [App\Http\Controllers\RecetaController::class, 'destroy'])->name('recetas.destroy');
    });
    
    // Mesero
    Route::middleware('role:mesero')->group(function () {
        Route::resource('pedidos', App\Http\Controllers\PedidoController::class);
    });
    
    // Cajero
    Route::middleware('role:cajero,gerente')->group(function () {
        Route::get('/pagos', function() { 
            return view('pagos.index'); 
        })->name('pagos.index');
        
        // Rutas para Cobrar Pedidos
        Route::get('/ventas/pedidos-pendientes', [App\Http\Controllers\VentaController::class, 'pendientes'])->name('ventas.pedidos');
        Route::post('/ventas/procesar-pedido/{id}', [App\Http\Controllers\VentaController::class, 'procesarPedido'])->name('ventas.procesar');

        Route::resource('ventas', App\Http\Controllers\VentaController::class);
        Route::resource('ventas', App\Http\Controllers\VentaController::class);

        // PagoFácil QR Routes
        Route::post('/ventas/{id}/generar-qr', [App\Http\Controllers\VentaController::class, 'generarQR'])->name('ventas.generarQR');
        Route::post('/ventas/{id}/verificar-pago', [App\Http\Controllers\VentaController::class, 'verificarEstado'])->name('ventas.verificarEstado');
        
        Route::get('/cajas', function() { 
            return view('cajas.index'); 
        })->name('cajas.index');
    });
    
    // Cocinero
    Route::middleware('role:cocinero')->group(function () {
        Route::get('/cocina/pedidos', [App\Http\Controllers\CocinaController::class, 'pedidos'])->name('cocina.pedidos');
        Route::patch('/cocina/pedidos/{id}/estado', [App\Http\Controllers\CocinaController::class, 'cambiarEstado'])->name('cocina.cambiar-estado');
    });

    // Cliente
    Route::middleware('role:cliente')->group(function () {
        Route::get('/cliente/dashboard', [App\Http\Controllers\ClienteController::class, 'index'])->name('cliente.dashboard');
        Route::get('/cliente/reservas', [App\Http\Controllers\ClienteController::class, 'indexReservas'])->name('cliente.reservas.index');
        Route::get('/cliente/reservas/crear', [App\Http\Controllers\ClienteController::class, 'createReserva'])->name('cliente.reservas.create');
        Route::post('/cliente/reservas', [App\Http\Controllers\ClienteController::class, 'storeReserva'])->name('cliente.reservas.store');
        Route::get('/cliente/pagos', [App\Http\Controllers\ClienteController::class, 'pagos'])->name('cliente.pagos.index');
        Route::post('/cliente/pagos', [App\Http\Controllers\ClienteController::class, 'realizarPago'])->name('cliente.pagos.store');
        Route::get('/cliente/mesas-disponibles', [App\Http\Controllers\ClienteController::class, 'getMesasDisponibles'])->name('cliente.reservas.disponibles');
    });
});
