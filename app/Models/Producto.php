<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
