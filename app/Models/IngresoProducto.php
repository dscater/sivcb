<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngresoProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        "origen",
        "producto_id",
        "proveedor_id",
        "precio",
        "cantidad",
        "tipo_ingreso_id",
        "descripcion",
        "lugar",
        "sucursal_id",
        "fecha_ingreso",
        "fecha_registro",
    ];

    protected $appends = ["fecha_ingreso_t", "fecha_registro_t"];

    public function getFechaIngresoTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_ingreso));
    }

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    // relaciones
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function tipo_ingreso()
    {
        return $this->belongsTo(TipoIngreso::class, 'tipo_ingreso_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function producto_barras()
    {
        return $this->hasMany(ProductoBarra::class, 'ingreso_id');
    }
}
