<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Insumo extends Model
{
    protected $table = 'insumos';
    protected $primaryKey = 'id_insumo';

    protected $fillable = [
        'nombre',
        'descripcion',
        'unidad_medida',
        'stock_actual',
        'stock_minimo',
        'precio_unitario',
        'id_proveedor',
    ];

    protected $casts = [
        'stock_actual' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
    ];

    // Relación con Productos a través de Recetas (Pivot)
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'recetas', 'id_insumo', 'id_producto')
                    ->withPivot('cantidad_requerida')
                    ->withTimestamps();
    }
}
