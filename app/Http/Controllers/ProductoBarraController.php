<?php

namespace App\Http\Controllers;

use App\Models\ProductoBarra;
use Illuminate\Http\Request;

class ProductoBarraController extends Controller
{
    public function getProductos(Request $request)
    {
        $producto_barras = ProductoBarra::with(["producto"])->select("producto_barras.*")
            ->where("salida_id", null)
            ->where("venta_detalle_id", null)
            ->get();

        return response()->JSON($producto_barras);
    }

    public function getByCod(Request $request)
    {
        $codigo = $request->codigo;
        $producto_barra = null;
        if ($codigo) {
            $producto_barra = ProductoBarra::where("codigo", $codigo)->get()->first();
        }
        return response()->JSON($producto_barra);
    }
}
