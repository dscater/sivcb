<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistribucionProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        "sucursal_id",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t"];

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function producto_barras()
    {
        return $this->hasMany(ProductoBarra::class, 'distribucion_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}
