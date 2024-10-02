<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoBarra extends Model
{
    use HasFactory;

    protected $fillable = [
        "producto_id",
        "codigo",
        "lugar",
        "sucursal_id",
        "ingreso_id",
        "salida_id",
        "venta_id",
        "venta_detalle_id",
        "distribucion_id",
    ];

    protected $appends = ["cod_prod"];

    public function getCodProdAttribute()
    {
        return $this->codigo . ' 1 ' . $this->producto->nombre;
    }

    // relaciones
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function ingreso()
    {
        return $this->belongsTo(IngresoProducto::class, 'ingreso_id');
    }

    public function salida()
    {
        return $this->belongsTo(SalidaProducto::class, 'salida_id');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function venta_detalle()
    {
        return $this->belongsTo(VentaDetalle::class, 'venta_detalle_id');
    }

    public function distribucion_producto()
    {
        return $this->belongsTo(DistribucionProducto::class, 'distribucion_id');
    }
}
