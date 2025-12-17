<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'tiempo_preparacion',
        'disponible',
        'imagen',
        'id_categoria',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'disponible' => 'boolean',
        'tiempo_preparacion' => 'integer',
    ];

    /**
     * Categoría del producto
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    /**
     * Detalles de pedidos que incluyen este producto
     */
    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class, 'id_producto', 'id_producto');
    }

    /**
     * Scope para productos disponibles
     */
    public function scopeDisponible($query)
    {
        return $query->where('disponible', true);
    }

    /**
     * Scope para búsqueda
     */
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'like', "%{$termino}%")
                    ->orWhere('descripcion', 'like', "%{$termino}%");
    }

    /**
     * Insumos requeridos (Receta)
     */
    public function insumos()
    {
        return $this->belongsToMany(Insumo::class, 'recetas', 'id_producto', 'id_insumo')
                    ->withPivot('cantidad_requerida')
                    ->withTimestamps();
    }
}
