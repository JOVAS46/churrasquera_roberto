<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    
    protected $fillable = [
        'nombre_rol',
        'descripcion',
    ];

    /**
     * Usuarios que tienen este rol
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_rol', 'id_rol');
    }

    /**
     * Items del menú asociados a este rol
     */
    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(
            MenuItem::class,
            'rol_menu',
            'id_rol',
            'id_menu',
            'id_rol',
            'id_menu'
        );
    }
}
