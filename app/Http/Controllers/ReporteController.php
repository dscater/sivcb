<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\KardexProducto;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Urbanizacion;
use App\Models\User;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\VentaLote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use PDF;

class ReporteController extends Controller
{
    public function usuarios()
    {
        return Inertia::render("Reportes/Usuarios");
    }

    public function r_usuarios(Request $request)
    {
        $tipo =  $request->tipo;
        $sucursal_id =  $request->sucursal_id;
        $usuarios = User::select("users.*")
            ->where('id', '!=', 1);

        if ($tipo != 'todos') {
            $request->validate([
                'tipo' => 'required',
            ]);
            $usuarios->where('tipo', $tipo);
        }

        if ($sucursal_id != 'todos') {
            $usuarios->where('sucursal_id', $sucursal_id);
        }

        $usuarios = $usuarios->orderBy("paterno", "ASC")->get();

        $pdf = PDF::loadView('reportes.usuarios', compact('usuarios'))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('usuarios.pdf');
    }

    public function stock_productos()
    {
        return Inertia::render("Reportes/StockProductos");
    }

    public function r_stock_productos(Request $request)
    {
        $lugar = $request->lugar;
        $categoria_id = $request->categoria_id;
        $marca_id = $request->marca_id;
        $unidad_medida_id = $request->unidad_medida_id;
        $sucursal_id = $request->sucursal_id;

        $productos = Producto::select("productos.*");
        if ($categoria_id != 'todos') {
            $productos->where("categoria_id", $categoria_id);
        }

        if ($marca_id != 'todos') {
            $productos->where("marca_id", $marca_id);
        }

        if ($unidad_medida_id != 'todos') {
            $productos->where("unidad_medida_id", $unidad_medida_id);
        }
        $productos = $productos->get();

        $sucursals = [];
        if ($lugar == 'SUCURSAL') {
            $sucursals = Sucursal::select("sucursals.*");
            if ($sucursal_id != 'todos') {
                $sucursals->where("id", $sucursal_id);
            }
            $sucursals = $sucursals->get();
        }

        $pdf = PDF::loadView('reportes.stock_productos', compact('productos', 'sucursals', 'lugar'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('stock_productos.pdf');
    }

    public function kardex_productos()
    {
        return Inertia::render("Reportes/KardexProductos");
    }

    public function r_kardex_productos(Request $request)
    {
        $producto_id = $request->producto_id;
        $categoria_id = $request->categoria_id;
        $marca_id = $request->marca_id;
        $unidad_medida_id = $request->unidad_medida_id;
        $sucursal_id = $request->sucursal_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        if ($request->filtro == 'Producto') {
            $request->validate([
                'producto_id' => 'required',
            ]);
        }

        if ($request->fecha_ini || $request->fecha_fin) {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
        }
        $productos = Producto::select("productos.*");
        if ($producto_id != 'todos') {
            $productos->where("id", $producto_id);
        }
        if ($categoria_id != 'todos') {
            $productos->where("categoria_id", $categoria_id);
        }

        if ($marca_id != 'todos') {
            $productos->where("marca_id", $marca_id);
        }

        if ($unidad_medida_id != 'todos') {
            $productos->where("unidad_medida_id", $unidad_medida_id);
        }
        $productos = $productos->get();

        $sucursals = Sucursal::select("sucursals.*");
        if ($sucursal_id != 'todos') {
            $sucursals->where("id", $sucursal_id);
        }
        $sucursals = $sucursals->get();

        $kardex_sucursals = [];
        foreach ($sucursals as $sucursal) {
            $kardex_sucursals[$sucursal->id] = [
                "array_kardex" => [],
                "array_saldo_anterior" => [],
            ];
            $array_kardex = [];
            $array_saldo_anterior = [];
            foreach ($productos as $registro) {
                $kardex = KardexProducto::where('producto_id', $registro->id)
                    ->where("sucursal_id", $sucursal->id)->get();
                $array_saldo_anterior[$registro->id] = [
                    'sw' => false,
                    'saldo_anterior' => []
                ];
                if ($fecha_ini && $fecha_fin) {
                    $kardex = KardexProducto::where('producto_id', $registro->id)
                        ->where("sucursal_id", $sucursal->id)
                        ->whereBetween('fecha', [$fecha_ini, $fecha_fin])->get();
                    // buscar saldo anterior si existe
                    $saldo_anterior = KardexProducto::where('producto_id', $registro->id)
                        ->where("sucursal_id", $sucursal->id)
                        ->where('fecha', '<', $fecha_ini)
                        ->orderBy('created_at', 'asc')->get()->last();
                    if ($saldo_anterior) {
                        $cantidad_saldo = $saldo_anterior->cantidad_saldo;
                        $monto_saldo = $saldo_anterior->monto_saldo;
                        $array_saldo_anterior[$registro->id] = [
                            'sw' => true,
                            'saldo_anterior' => [
                                'cantidad_saldo' => $cantidad_saldo,
                                'monto_saldo' => $monto_saldo,
                            ]
                        ];
                    }
                }

                $array_kardex[$registro->id] = $kardex;
            }
            $kardex_sucursals[$sucursal->id]["array_kardex"] = $array_kardex;
            $kardex_sucursals[$sucursal->id]["array_saldo_anterior"] = $array_saldo_anterior;
        }

        $pdf = PDF::loadView('reportes.kardex_productos', compact('productos', 'sucursals', "kardex_sucursals"))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('kardex.pdf');
    }

    public function ventas()
    {
        return Inertia::render("Reportes/Ventas");
    }

    public function r_ventas(Request $request)
    {
        $producto_id = $request->producto_id;
        $categoria_id = $request->categoria_id;
        $marca_id = $request->marca_id;
        $unidad_medida_id = $request->unidad_medida_id;
        $sucursal_id = $request->sucursal_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        if ($request->filtro == 'Producto') {
            $request->validate([
                'producto_id' => 'required',
            ]);
        }

        if ($request->fecha_ini || $request->fecha_fin) {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
        }
        $productos = Producto::select("productos.*");
        if ($producto_id != 'todos') {
            $productos->where("id", $producto_id);
        }
        if ($categoria_id != 'todos') {
            $productos->where("categoria_id", $categoria_id);
        }

        if ($marca_id != 'todos') {
            $productos->where("marca_id", $marca_id);
        }

        if ($unidad_medida_id != 'todos') {
            $productos->where("unidad_medida_id", $unidad_medida_id);
        }
        $productos = $productos->get();

        $sucursals = Sucursal::select("sucursals.*");
        if ($sucursal_id != 'todos') {
            $sucursals->where("id", $sucursal_id);
        }
        $sucursals = $sucursals->get();

        $venta_sucursals = [];
        foreach ($sucursals as $sucursal) {
            $venta_sucursals[$sucursal->id] = [
                "array_ventas" => [],
            ];
            $array_ventas = [];
            foreach ($productos as $registro) {
                $venta_detalles = VentaDetalle::select("venta_detalles.*")
                    ->join("ventas", "ventas.id", "=", "venta_detalles.venta_id")
                    ->where('venta_detalles.producto_id', $registro->id)
                    ->where("ventas.sucursal_id", $sucursal->id);
                if ($fecha_ini && $fecha_fin) {
                    $venta_detalles->whereBetween("ventas.fecha_registro", [$fecha_ini, $fecha_fin]);
                }
                $venta_detalles  = $venta_detalles->get();
                $array_ventas[$registro->id] = $venta_detalles;
            }
            $venta_sucursals[$sucursal->id]["array_ventas"] = $array_ventas;
        }

        $pdf = PDF::loadView('reportes.ventas', compact('productos', 'sucursals', "venta_sucursals"))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('ventas.pdf');
    }

    public function g_ventas(Request $request)
    {
        $producto_id = $request->producto_id;
        $categoria_id = $request->categoria_id;
        $marca_id = $request->marca_id;
        $unidad_medida_id = $request->unidad_medida_id;
        $sucursal_id = $request->sucursal_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        if ($request->filtro == 'Producto') {
            $request->validate([
                'producto_id' => 'required',
            ]);
        }

        if ($request->fecha_ini || $request->fecha_fin) {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
        }

        $sucursals = Sucursal::select("sucursals.*");
        if ($sucursal_id != 'todos') {
            $sucursals->where("id", $sucursal_id);
        }
        $sucursals = $sucursals->get();

        $categories = [];
        $series = [
            [
                "name" => "Cantidad vendida",
                "data" => []
            ],
            [
                "name" => "Total ingresos",
                "data" => []
            ]
        ];
        foreach ($sucursals as $sucursal) {
            $categories[] = $sucursal->nombre;

            $venta_detalles = VentaDetalle::select("venta_detalles.*")
                ->join("ventas", "ventas.id", "=", "venta_detalles.venta_id")
                ->join("productos", "productos.id", "=", "venta_detalles.producto_id")
                ->where("ventas.sucursal_id", $sucursal->id);
            if ($producto_id != 'todos') {
                $venta_detalles->where('venta_detalles.producto_id', $producto_id->id);
            }
            if ($categoria_id != 'todos') {
                $venta_detalles->where('productos.categoria_id', $categoria_id);
            }

            if ($marca_id != 'todos') {
                $venta_detalles->where('productos.marca_id', $marca_id);
            }

            if ($unidad_medida_id != 'todos') {
                $venta_detalles->where('productos.unidad_medida_id', $unidad_medida_id);
            }

            if ($fecha_ini && $fecha_fin) {
                $venta_detalles->whereBetween("ventas.fecha_registro", [$fecha_ini, $fecha_fin]);
            }
            $venta_detalles_c  = $venta_detalles->sum("cantidad");
            $venta_detalles_m  = $venta_detalles->sum("subtotaltotal");
            $series[0]["data"][] = (float)$venta_detalles_c;
            $series[1]["data"][] = (float)$venta_detalles_m;
        }

        return response()->JSON([
            "categories" => $categories,
            "series" => $series
        ]);
    }

    public function r_pdf_stock_productos(Request $request)
    {
        $urbanizacion_id =  $request->urbanizacion_id;

        $urbanizacions = Urbanizacion::select("urbanizacions.*");

        if ($urbanizacion_id != 'todos') {
            $urbanizacions->where("id", $urbanizacion_id);
        }

        $urbanizacions = $urbanizacions->get();

        $pdf = PDF::loadView('reportes.pdf_stock_productos', compact('urbanizacions'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('stock_productos.pdf');
    }

    public function g_venta_lotes()
    {
        return Inertia::render("Reportes/GVentaLotes");
    }

    public function r_g_venta_lotes(Request $request)
    {
        $urbanizacion_id =  $request->urbanizacion_id;

        $urbanizacions = Urbanizacion::select("urbanizacions.*");

        if ($urbanizacion_id != 'todos') {
            $urbanizacions->where("id", $urbanizacion_id);
        }

        $urbanizacions = $urbanizacions->get();
        $categories = [];
        $series = [
            [
                "name" => "Total",
                "data" => [],
                "color" => "#06bb7f",
            ],
        ];
        foreach ($urbanizacions as $item) {
            $categories[] = $item->nombre;
            $total = VentaLote::where("urbanizacion_id", $item->id)->sum("total_venta");
            $series[0]["data"][] = (float)$total;
        }

        return response()->JSON([
            "categories" => $categories,
            "series" => $series,
        ]);
    }

    public function r_pdf_venta_lotes(Request $request)
    {
        $urbanizacion_id =  $request->urbanizacion_id;

        $urbanizacions = Urbanizacion::select("urbanizacions.*");

        if ($urbanizacion_id != 'todos') {
            $urbanizacions->where("id", $urbanizacion_id);
        }

        $urbanizacions = $urbanizacions->get();

        $pdf = PDF::loadView('reportes.venta_lotes', compact('urbanizacions'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('venta_lotes.pdf');
    }
}
