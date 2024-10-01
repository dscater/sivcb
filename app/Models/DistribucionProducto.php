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
}
