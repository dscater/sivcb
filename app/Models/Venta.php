<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        "sucursal_id",
        "cliente_id",
        "user_id",
        "nit",
        "total",
        "descuento",
        "total_final",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t","fecha_hora_t", "qr"];

    public function getQrAttribute()
    {
        $codigo = $this->id . "|" . $this->cliente->nombre . "|" . $this->nit . "|" . $this->fecha_registro . "|" . $this->total_final;
        $url_base64 = "data:image/png;base64," . base64_encode(QrCode::format("png")->size(150)->generate($codigo));
        return $url_base64;
    }

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function getFechaHoraTAttribute()
    {
        return date("d/m/Y H:i", strtotime($this->fecha_registro));
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function venta_detalles()
    {
        return $this->hasMany(VentaDetalle::class, 'venta_id');
    }

    public function producto_barras()
    {
        return $this->hasMany(ProductoBarra::class, 'venta_id');
    }
}
