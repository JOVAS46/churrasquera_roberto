<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MenuItem extends Model
{
    protected $table = 'menu_items';
    protected $primaryKey = 'id_menu';
    
    protected $fillable = [
        'nombre',
        'ruta',
        'icono',
        'orden',
        'activo',
        'parent_id',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
    ];

    /**
     * Item padre (para submenús)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id', 'id_menu');
    }

    /**
     * Items hijos (submenús)
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id', 'id_menu')
                    ->orderBy('orden');
    }

    /**
     * Roles que tienen acceso a este item
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'rol_menu',
            'id_menu',
            'id_rol',
            'id_menu',
            'id_rol'
        );
    }

    /**
     * Scope para items activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para items principales (sin padre)
     */
    public function scopePrincipal($query)
    {
        return $query->whereNull('parent_id');
    }
}
