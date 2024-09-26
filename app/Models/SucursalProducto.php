<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SucursalProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        "producto_id",
        "sucursal_id",
        "stock_actual",
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Producto::class, 'sucursal_id');
    }
}
