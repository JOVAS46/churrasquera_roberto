<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'password',
        'estado',
        'id_rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fecha_registro' => 'datetime',
    ];

    /**
     * Rol del usuario
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_rol', 'id_rol');
    }

    /**
     * Preferencias del usuario
     */
    public function preferencias(): HasOne
    {
        return $this->hasOne(UserPreference::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Pedidos como mesero
     */
    public function pedidosComoMesero(): HasMany
    {
        return $this->hasMany(Pedido::class, 'id_mesero', 'id_usuario');
    }

    /**
     * Pedidos como cliente
     */
    public function pedidosComoCliente(): HasMany
    {
        return $this->hasMany(Pedido::class, 'id_cliente', 'id_usuario');
    }

    /**
     * Visitas a páginas
     */
    public function visitas(): HasMany
    {
        return $this->hasMany(VisitaPagina::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Obtener nombre completo
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }
}
