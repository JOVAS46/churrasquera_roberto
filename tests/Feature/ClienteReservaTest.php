<?php

namespace Tests\Feature;

use App\Models\Mesa;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClienteReservaTest extends TestCase
{
    use RefreshDatabase;

    public function test_cliente_puede_crear_reserva()
    {
        // 1. Crear Rol Cliente y Mesa si no existen
        $role = Role::firstOrCreate(['nombre_rol' => 'cliente'], ['descripcion' => 'Cliente Test']);
        $mesa = Mesa::firstOrCreate(['numero_mesa' => 'Test-1'], ['capacidad' => 4, 'estado' => 'disponible', 'ubicacion' => 'salon']);

        // 2. Crear Usuario Cliente
        $cliente = Usuario::create([
            'nombre' => 'Test',
            'apellido' => 'Cliente',
            'email' => 'testcliente' . uniqid() . '@test.com',
            'password' => bcrypt('password'),
            'telefono' => '12345678',
            'estado' => true,
            'id_rol' => $role->id_rol
        ]);

        // 3. Autenticar
        $response = $this->actingAs($cliente);

        // 4. Enviar Petición de Reserva
        $fecha = date('Y-m-d', strtotime('+1 day'));
        try {
            $response = $this->post(route('cliente.reservas.store'), [
                'fecha' => $fecha,
                'hora' => '14:00',
                'personas' => 2,
                'notas' => 'Reserva de prueba'
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        // 5. Verificar Redirección y Base de Datos
        $response->assertRedirect(route('cliente.reservas.index'));
        
        $this->assertDatabaseHas('reservas', [
            'id_cliente' => $cliente->id_usuario,
            'fecha_reserva' => $fecha,
            'numero_personas' => 2,
            'estado' => 'pendiente',
            'id_mesa' => $mesa->id_mesa
        ]);

        // Cleanup (Optional but good practice if not using RefreshDatabase)
        // Usuario::where('id_usuario', $cliente->id_usuario)->delete();
        // Reserva::where('id_cliente', $cliente->id_usuario)->delete();
    }
}
