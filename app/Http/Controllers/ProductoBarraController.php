<?php

namespace App\Http\Controllers;

use App\Models\ProductoBarra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductoBarraController extends Controller
{
    public function getProductos(Request $request)
    {
        $producto_barras = ProductoBarra::with(["producto"])->select("producto_barras.*")
            ->where("salida_id", null)
            ->where("venta_detalle_id", null);

        if (Auth::user()->tipo != 'ADMINISTRADOR') {
            $producto_barras->where("sucursal_id", Auth::user()->sucursal_id);
            if (Auth::user()->tipo == 'SUPERVISOR DE SUCURSAL') {
                $producto_barras->orWhere("sucursal_id", nulL);
            }
        }

        $producto_barras = $producto_barras->get();

        return response()->JSON($producto_barras);
    }

    public function getByCod(Request $request)
    {
        $codigo = $request->codigo;
        $producto_barra = null;
        if ($codigo) {
            $producto_barra = ProductoBarra::where("codigo", $codigo);
            if (isset($request->almacen) && $request->almacen) {
                $producto_barra->where("lugar", "ALMACÉN");
                $producto_barra->where("distribucion_id", null);
            }
            if (isset($request->sucursal_id) && $request->sucursal_id) {
                $producto_barra->where("lugar", "SUCURSAL");
                $producto_barra->where("sucursal_id", $request->sucursal_id);
            }
            if (isset($request->venta) && $request->venta) {
                $producto_barra->where("salida_id", null);
                $producto_barra->where("venta_id", null);
                $producto_barra->where("venta_detalle_id", null);
            }
            $producto_barra = $producto_barra->get()->first();
            if ($producto_barra) {
                if (isset($request->venta) && $request->venta) {
                    $producto_barra = $producto_barra->load(["producto"]);
                }
                return response()->JSON($producto_barra);
            }
        }
        return response()->JSON(null);
    }
}
