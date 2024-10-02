<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        "venta_id",
        "producto_id",
        "cantidad",
        "precio",
        "subtotal",
        "descuento",
        "subtotaltotal",
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function producto_barras()
    {
        return $this->hasMany(ProductoBarra::class, 'venta_detalle_id');
    }
}
