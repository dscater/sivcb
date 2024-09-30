<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidaProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        "producto_id",
        "cantidad",
        "fecha_salida",
        "tipo_salida_id",
        "descripcion",
        "lugar",
        "sucursal_id",
        "fecha_registro",
    ];

    protected $appends = ["fecha_salida_t", "fecha_registro_t"];

    public function getFechaSalidaTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_salida));
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

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function tipo_salida()
    {
        return $this->belongsTo(TipoSalida::class, 'tipo_salida_id');
    }

    public function producto_barras()
    {
        return $this->hasMany(ProductoBarra::class, 'salida_id');
    }
}
