<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "categoria_id",
        "marca_id",
        "unidad_medida_id",
        "precio",
        "stock_min",
        "imagen",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t", "url_foto", "foto_b64"];

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function getUrlFotoAttribute()
    {
        if ($this->imagen) {
            return asset("imgs/productos/" . $this->imagen);
        }
        return asset("imgs/productos/default.png");
    }

    public function getFotoB64Attribute()
    {
        $path = public_path("imgs/productos/" . $this->imagen);
        if (!$this->imagen || !file_exists($path)) {
            $path = public_path("imgs/productos/default.png");
        }
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
    // FUNCIONES PARA INCREMETAR Y DECREMENTAR EL STOCK
    public static function incrementarStock($producto, $cantidad, $lugar, $sucursal_id = null)
    {
        if ($lugar == 'ALMACÉN') {
            if (!$producto->almacen_producto) {
                $producto->almacen_producto()->create([
                    "stock_actual" => $cantidad
                ]);
            } else {
                $producto->almacen_producto->stock_actual = (float)$producto->almacen_producto->stock_actual + $cantidad;
                $producto->almacen_producto->save();
            }
        } else {
            $sucursal_producto = SucursalProducto::where("producto_id", $producto->id)
                ->where("sucursal_id", $sucursal_id)
                ->get()->first();
            if (!$sucursal_producto) {
                $producto->sucursal_productos()->create([
                    "sucursal_id" => $sucursal_id,
                    "stock_actual" => $cantidad,
                ]);
            } else {
                $sucursal_producto->stock_actual = (float)$sucursal_producto->stock_actual + $cantidad;
                $sucursal_producto->save();
            }
        }
        return true;
    }
    public static function decrementarStock($producto, $cantidad, $lugar, $sucursal_id = null)
    {
        if ($lugar == 'ALMACÉN') {
            $producto->almacen_producto->stock_actual = (float)$producto->almacen_producto->stock_actual - $cantidad;
            $producto->almacen_producto->save();
        } else {
            $sucursal_producto = SucursalProducto::where("producto_id", $producto->id)
                ->where("sucursal_id", $sucursal_id)
                ->get()->first();
            if ($sucursal_producto) {
                $sucursal_producto->stock_actual = (float)$sucursal_producto->stock_actual - $cantidad;
                $sucursal_producto->save();
            }
        }
        return true;
    }

    // relaciones
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function unidad_medida()
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

    public function almacen_producto()
    {
        return $this->hasOne(AlmacenProducto::class, 'producto_id');
    }

    public function sucursal_productos()
    {
        return $this->hasMany(SucursalProducto::class, 'producto_id');
    }
}
