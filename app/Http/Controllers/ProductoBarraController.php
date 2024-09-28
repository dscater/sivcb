<?php

namespace App\Http\Controllers;

use App\Models\ProductoBarra;
use Illuminate\Http\Request;

class ProductoBarraController extends Controller
{
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
