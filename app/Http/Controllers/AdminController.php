<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Role;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Dashboard de administrador
     */
    public function index()
    {
        $stats = [
            'total_usuarios' => Usuario::count(),
            'usuarios_activos' => Usuario::where('estado', true)->count(),
            'total_pedidos' => Pedido::count(),
            'pedidos_hoy' => Pedido::whereDate('fecha_pedido', today())->count(),
            'total_productos' => Producto::count(),
            'productos_disponibles' => Producto::disponible()->count(),
            'ventas_hoy' => Pago::whereDate('created_at', today())->sum('monto'),
            'ventas_mes' => Pago::whereMonth('created_at', now()->month)->sum('monto'),
        ];

        // Pedidos recientes
        $pedidosRecientes = Pedido::with(['cliente', 'mesero', 'mesa'])
            ->latest('fecha_pedido')
            ->take(10)
            ->get();

        // Productos más vendidos
        $productosMasVendidos = Producto::withCount('detallesPedido')
            ->orderBy('detalles_pedido_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pedidosRecientes', 'productosMasVendidos'));
    }

    /**
     * Lista de usuarios
     */
    public function usuarios()
    {
        $usuarios = Usuario::with('role')
            ->latest()
            ->paginate(15);

        $roles = Role::all();

        return view('admin.usuarios.index', compact('usuarios', 'roles'));
    }

    /**
     * Crear usuario
     */
    public function crearUsuario(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'id_rol' => 'required|exists:roles,id_rol',
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'estado' => true,
            'id_rol' => $request->id_rol,
        ]);

        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Actualizar usuario
     */
    public function actualizarUsuario(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email,' . $id . ',id_usuario',
            'telefono' => 'nullable|string|max:20',
            'id_rol' => 'required|exists:roles,id_rol',
            'password' => 'nullable|string|min:6',
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
        $usuario->id_rol = $request->id_rol;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Cambiar estado de usuario
     */
    public function cambiarEstadoUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->estado = !$usuario->estado;
        $usuario->save();

        $mensaje = $usuario->estado ? 'Usuario activado' : 'Usuario desactivado';

        return redirect()->route('admin.usuarios')
            ->with('success', $mensaje);
    }

    /**
     * Eliminar usuario
     */
    public function eliminarUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);
        
        // No permitir eliminar al propio usuario
        if ($usuario->id_usuario === auth()->id()) {
            return redirect()->route('admin.usuarios')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Reportes
     */
    public function reportes(Request $request)
    {
        // 1. Filtros de Fecha
        $fechaInicio = $request->input('fecha_inicio', now()->subDays(7)->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));

        // 2. Ventas por día (Filtradas)
        $ventasPorDia = Pago::selectRaw('DATE(created_at) as fecha, SUM(monto) as total')
            ->whereDate('created_at', '>=', $fechaInicio)
            ->whereDate('created_at', '<=', $fechaFin)
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // 3. Productos más vendidos (Global, se podría filtrar también si se desea)
        $productosMasVendidos = Producto::withCount(['detallesPedido' => function ($query) use ($fechaInicio, $fechaFin) {
                // Filtrar conteo por fecha de pedido
                $query->whereHas('pedido', function ($q) use ($fechaInicio, $fechaFin) {
                    $q->whereDate('fecha_pedido', '>=', $fechaInicio)
                      ->whereDate('fecha_pedido', '<=', $fechaFin);
                });
            }])
            ->orderBy('detalles_pedido_count', 'desc')
            ->take(10)
            ->get();

        // 4. Desempeño por mesero (Filtrado)
        $desempenoMeseros = Usuario::whereHas('role', function($q) {
                $q->where('nombre_rol', 'Mesero');
            })
            ->withCount(['pedidosComoMesero' => function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereDate('fecha_pedido', '>=', $fechaInicio)
                      ->whereDate('fecha_pedido', '<=', $fechaFin);
            }])
            ->orderBy('pedidos_como_mesero_count', 'desc')
            ->get();

        return view('admin.reportes.index', compact('ventasPorDia', 'productosMasVendidos', 'desempenoMeseros', 'fechaInicio', 'fechaFin'));
    }
}
