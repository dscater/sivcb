<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlmacenProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        "producto_id",
        "stock_actual",
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
