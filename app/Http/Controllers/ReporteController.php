<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\IngresoProducto;
use App\Models\KardexProducto;
use App\Models\Lote;
use App\Models\Producto;
use App\Models\SalidaProducto;
use App\Models\Sucursal;
use App\Models\SucursalProducto;
use App\Models\Urbanizacion;
use App\Models\User;
use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\VentaLote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Services\ReporteService;
use App\Services\ReporteServiceTcpdf;
use Illuminate\Support\Facades\Auth;
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
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $lugar = $request->lugar;
        $categoria_id = $request->categoria_id;
        $marca_id = $request->marca_id;
        $unidad_medida_id = $request->unidad_medida_id;
        $sucursal_id = $request->sucursal_id;

        $productos = Producto::select(
            "productos.id",
            "productos.nombre",
            "productos.precio",
            "productos.categoria_id",
            "productos.marca_id",
            "productos.unidad_medida_id",
            "productos.precio",
        )
            ->with([
                "categoria:id,nombre",
                "marca:id,nombre",
                "unidad_medida:id,nombre",
                "almacen_producto"
            ]);
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

        $pdf = new ReporteServiceTcpdf();
        $pdf->SetTitle('Stock de Productos');
        $pdf->setMargins(10, 5, 5);
        $pdf->AddPage();
        $pdf->setPrintHeader(false);
        $pdf->setY(13);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 6, "STOCK DE PRODUCTOS", 0, 1, 'C', 0, '', 0, false);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 5, "Expedido: " . date("d/m/Y"), 0, 1, 'C', 0, '', 0, false);
        $pdf->SetFont('helvetica', 'B', 8);
        $alto = 10;
        $font_size = 8;
        $font_size2 = 9;
        if ($lugar == 'ALMACÉN') {
            $pdf->setFont("helvetica", "B", $font_size2);
            $pdf->cell(192, $alto, "STOCK DE ALMACÉN", 1, 1);
            $pdf->cell(12, $alto, '#', 1, 0, 'C');
            $pdf->cell(35, $alto, 'PRODUCTO', 1, 0, 'C');
            $pdf->cell(25, $alto, 'CATEGORÍA', 1, 0, 'C');
            $pdf->cell(25, $alto, 'MARCA', 1, 0, 'C');
            $pdf->cellAutoFontSize($pdf, 25, $alto, 'UNIDAD MEDIDA', $font_size, 5, 'B');
            $pdf->cell(25, $alto, 'PRECIO', 1, 0, 'C');
            $pdf->cellAutoFontSize($pdf, 25, $alto, 'STOCK ACTUAL', $font_size, 5, 'B');
            $pdf->cell(20, $alto, 'TOTAL', 1, 1, 'C');
            $cont = 1;
            $sum_total_c = 0;
            $sum_total_t = 0;
            $pdf->SetFont('helvetica', 'N', $font_size);
            foreach ($productos as $producto) {
                $pdf->cellAutoFontSize($pdf, 12, $alto, $cont++, $font_size);
                $pdf->cellAutoFontSize($pdf, 35, $alto, $producto->nombre, $font_size);
                $pdf->cellAutoFontSize($pdf, 25, $alto, $producto->categoria->nombre, $font_size);
                $pdf->cellAutoFontSize($pdf, 25, $alto, $producto->marca->nombre, $font_size);
                $pdf->cellAutoFontSize($pdf, 25, $alto, $producto->unidad_medida->nombre, $font_size);
                $pdf->cellAutoFontSize($pdf, 25, $alto, $producto->precio, $font_size);
                $pdf->cellAutoFontSize($pdf, 25, $alto, ($producto->almacen_producto ? $producto->almacen_producto->stock_actual : 0), $font_size);
                $total = (float) $producto->precio * ($producto->almacen_producto ? $producto->almacen_producto->stock_actual : 0);
                $pdf->cellAutoFontSize($pdf, 20, $alto, $total);
                $pdf->Ln();
                $sum_total_c += (float) ($producto->almacen_producto ? $producto->almacen_producto->stock_actual : 0);
                $sum_total_t += (float) $total;
            }
            $pdf->setFont("helvetica", "B", $font_size2);
            $pdf->cell(147, $alto, "TOTALES", 1, 0, 'R');
            $pdf->cellAutoFontSize($pdf, 25, $alto, $sum_total_c, $font_size);
            $pdf->cellAutoFontSize($pdf, 20, $alto, $sum_total_t, $font_size);
        } else {
            $html = '';
            foreach ($sucursals as $sucursal) {
                $pdf->setFont("helvetica", "B", $font_size2);
                $pdf->cell(192, $alto, $sucursal->nombre, 1, 1);
                $pdf->cell(12, $alto, '#', 1, 0, 'C');
                $pdf->cell(35, $alto, 'PRODUCTO', 1, 0, 'C');
                $pdf->cell(25, $alto, 'CATEGORÍA', 1, 0, 'C');
                $pdf->cell(25, $alto, 'MARCA', 1, 0, 'C');
                $pdf->cellAutoFontSize($pdf, 25, $alto, 'UNIDAD MEDIDA', $font_size, 5, 'B');
                $pdf->cell(25, $alto, 'PRECIO', 1, 0, 'C');
                $pdf->cellAutoFontSize($pdf, 25, $alto, 'STOCK ACTUAL', $font_size, 5, 'B');
                $pdf->cell(20, $alto, 'TOTAL', 1, 1, 'C');
                $cont = 1;
                $sum_total_c = 0;
                $sum_total_t = 0;

                foreach ($productos as $producto) {
                    $sucursal_producto = SucursalProducto::where('producto_id', $producto->id)
                        ->where('sucursal_id', $sucursal->id)
                        ->get()
                        ->first();

                    $pdf->cellAutoFontSize($pdf, 12, $alto, $cont++, $font_size);
                    $pdf->cellAutoFontSize($pdf, 35, $alto, $producto->nombre, $font_size);
                    $pdf->cellAutoFontSize($pdf, 25, $alto, $producto->categoria->nombre, $font_size);
                    $pdf->cellAutoFontSize($pdf, 25, $alto, $producto->marca->nombre, $font_size);
                    $pdf->cellAutoFontSize($pdf, 25, $alto, $producto->unidad_medida->nombre, $font_size);
                    $pdf->cellAutoFontSize($pdf, 25, $alto, $producto->precio, $font_size);
                    $pdf->cellAutoFontSize($pdf, 25, $alto, ($sucursal_producto ? $sucursal_producto->stock_actual : 0), $font_size);
                    $total = (float) $producto->precio * ($sucursal_producto ? $sucursal_producto->stock_actual : 0);
                    $pdf->cellAutoFontSize($pdf, 20, $alto, $total);
                    $pdf->Ln();
                    $sum_total_c += (float) ($sucursal_producto ? $sucursal_producto->stock_actual : 0);
                    $sum_total_t += (float) $total;
                }
                $pdf->setFont("helvetica", "B", $font_size2);
                $pdf->cell(147, $alto, "TOTALES", 1, 0, 'R');
                $pdf->cellAutoFontSize($pdf, 25, $alto, $sum_total_c, $font_size);
                $pdf->cellAutoFontSize($pdf, 20, $alto, $sum_total_t, $font_size);
            }
            $pdf->writeHTML($html, true, false, true, false, '');
        }


        // Guardar PDF o forzar descarga
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="stock.pdf"');
        // $pdf = PDF::loadView('reportes.stock_productos', compact('productos', 'sucursals', 'lugar'))->setPaper('letter', 'portrait');
        // 
        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        // $pdf->output();
        // $dom_pdf = $pdf->getDomPDF();
        // $canvas = $dom_pdf->get_canvas();
        // $alto = $canvas->get_height();
        // $ancho = $canvas->get_width();
        // $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        // return $pdf->stream('stock_productos.pdf');
    }

    public function kardex_productos()
    {
        return Inertia::render("Reportes/KardexProductos");
    }

    public function r_kardex_productos(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
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
        $productos = Producto::select(
            "productos.id",
            "productos.nombre",
            "productos.precio",
            "productos.categoria_id",
            "productos.marca_id",
            "productos.unidad_medida_id",
            "productos.precio",
        )
            ->with([
                "categoria:id,nombre",
                "marca:id,nombre",
                "unidad_medida:id,nombre",
                "almacen_producto"
            ]);
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

        $sucursals = Sucursal::select("sucursals.id", "sucursals.nombre");
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

        $array_dias = [
            '0' => 'Domingo',
            '1' => 'Lunes',
            '2' => 'Martes',
            '3' => 'Miércoles',
            '4' => 'Jueves',
            '5' => 'Viernes',
            '6' => 'Sábado',
        ];
        $array_meses = [
            '01' => 'enero',
            '02' => 'febrero',
            '03' => 'marzo',
            '04' => 'abril',
            '05' => 'mayo',
            '06' => 'junio',
            '07' => 'julio',
            '08' => 'agosto',
            '09' => 'septiembre',
            '10' => 'octubre',
            '11' => 'noviembre',
            '12' => 'diciembre',
        ];
        $contador_su = 0;

        $pdf = new ReporteServiceTcpdf();
        $pdf->SetTitle('Kardex de Productos');
        $pdf->setMargins(10, 5, 5);

        foreach ($sucursals as $sucursal) {
            $pdf->AddPage();
            $pdf->setPrintHeader(false);
            $pdf->setY(13);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 6, "KARDEX DE PRODUCTOS", 0, 1, 'C', 0, '', 0, false);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 5, $sucursal->nombre, 0, 1, 'C', 0, '', 0, false);
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->Cell(0, 5, $array_dias[date('w')] . ', ' . date('d') . ' de ' . $array_meses[date('m')] . ' de ' . date('Y'), 0, 1, 'C', 0, '', 0, false);
            $pdf->Cell(0, 5, "(Expresado en bolivianos)", 0, 1, 'C', 0, '', 0, false);
            $pdf->SetFont('helvetica', 'B', 8);
            $alto = 10;
            $font_size = 8;
            $font_size2 = 9;

            $html = '';

            foreach ($productos as $registro) {
                $pdf->SetFont('helvetica', 'B', $font_size2);
                $pdf->Cell(190, 10, $registro->nombre, 1, 1, 'C');
                $pdf->MultiCell(15, 20, 'FECHA', 1, 'C', false, 0, $x = '',  $y = '',  $reseth = true,  $stretch = 0,  $ishtml = false,  $autopadding = true,  $maxh = 0,  $valign = 'M',  $fitcell = false);
                $pdf->MultiCell(35, 20, 'DETALLE', 1, 'C', false, 0);
                $pdf->cellAutoFontSize($pdf, 60, $alto, 'CANTIDADES', $font_size, 5, "B");
                $pdf->cellAutoFontSize($pdf, 20, $alto, 'P/U', $font_size, 5, "B");
                $pdf->cellAutoFontSize($pdf, 60, $alto, 'BOLIVIANOS', $font_size, 5, "B");
                $pdf->Ln();
                $pdf->SetX(60);
                $pdf->cellAutoFontSize($pdf, 20, $alto, 'ENTRADA', $font_size, 5, "B");
                $pdf->cellAutoFontSize($pdf, 20, $alto, 'SALIDA', $font_size, 5, "B");
                $pdf->cellAutoFontSize($pdf, 20, $alto, 'SALDO', $font_size, 5, "B");
                $pdf->SetX(140);
                $pdf->cellAutoFontSize($pdf, 20, $alto, 'ENTRADA', $font_size, 5, "B");
                $pdf->cellAutoFontSize($pdf, 20, $alto, 'SALIDA', $font_size, 5, "B");
                $pdf->cellAutoFontSize($pdf, 20, $alto, 'SALDO', $font_size, 5, "B");
                $pdf->Ln();

                if (count($kardex_sucursals[$sucursal->id]['array_kardex'][$registro->id]) > 0 || $kardex_sucursals[$sucursal->id]['array_saldo_anterior'][$registro->id]['sw']) {

                    $total = 0;
                    if ($kardex_sucursals[$sucursal->id]['array_saldo_anterior'][$registro->id]['sw']) {
                        $pdf->cellAutoFontSize($pdf, 15, $alto, "",);
                        $pdf->cellAutoFontSize($pdf, 35, $alto, "SALDO ANTERIOR",);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, "",);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, "",);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, $kardex_sucursals[$sucursal->id]['array_saldo_anterior'][$registro->id]['saldo_anterior']['cantidad_saldo'],);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, $registro->precio,);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, "",);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, "",);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, number_format($kardex_sucursals[$sucursal->id]['array_saldo_anterior'][$registro->id]['saldo_anterior']['monto_saldo'], 2, '.', ','),);
                        $pdf->Ln();
                    }
                    foreach ($kardex_sucursals[$sucursal->id]['array_kardex'][$registro->id] as $value) {
                        $pdf->cellAutoFontSize($pdf, 15, $alto, date("d/m/Y", strtotime($value["fecha"])));
                        $pdf->cellAutoFontSize($pdf, 35, $alto, $value["detalle"]);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, $value["cantidad_ingreso"]);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, $value["cantidad_salida"]);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, $value["cantidad_saldo"]);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, number_format($value["cu"], 2, '.', ','));
                        $pdf->cellAutoFontSize($pdf, 20, $alto, $value["monto_ingreso"]);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, $value["monto_salida"]);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, number_format($value["monto_saldo"], 2, '.', ','));
                        $pdf->Ln();
                    }
                } else {
                    $pdf->Cell(190, 10, "NO SE ENCONTRARON REGISTROS", 1, 1);
                }
                $pdf->Ln();
            }
        }

        // Guardar PDF o forzar descarga
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="kardex.pdf"');

        // $pdf = PDF::loadView('reportes.kardex_productos', compact('productos', 'sucursals', "kardex_sucursals"))->setPaper('letter', 'portrait');

        // // ENUMERAR LAS PÁGINAS
        // $pdf->output();
        // $dom_pdf = $pdf->getDomPDF();
        // $canvas = $dom_pdf->get_canvas();
        // $alto = $canvas->get_height();
        // $ancho = $canvas->get_width();
        // $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        // return $pdf->stream('kardex.pdf');
    }

    public function ventas()
    {
        return Inertia::render("Reportes/Ventas");
    }

    public function r_ventas(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $producto_id = $request->producto_id;
        $categoria_id = $request->categoria_id;
        $marca_id = $request->marca_id;
        $unidad_medida_id = $request->unidad_medida_id;
        $sucursal_id = $request->sucursal_id;
        $tipo_pago = $request->tipo_pago;
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
                if ($tipo_pago != 'todos') {
                    $venta_detalles->where("ventas.tipo_pago", $tipo_pago);
                }
                $venta_detalles  = $venta_detalles->get();
                $array_ventas[$registro->id] = $venta_detalles;
            }
            $venta_sucursals[$sucursal->id]["array_ventas"] = $array_ventas;
        }

        $array_dias = [
            '0' => 'Domingo',
            '1' => 'Lunes',
            '2' => 'Martes',
            '3' => 'Miércoles',
            '4' => 'Jueves',
            '5' => 'Viernes',
            '6' => 'Sábado',
        ];
        $array_meses = [
            '01' => 'enero',
            '02' => 'febrero',
            '03' => 'marzo',
            '04' => 'abril',
            '05' => 'mayo',
            '06' => 'junio',
            '07' => 'julio',
            '08' => 'agosto',
            '09' => 'septiembre',
            '10' => 'octubre',
            '11' => 'noviembre',
            '12' => 'diciembre',
        ];
        $contador_su = 0;

        $pdf = new ReporteServiceTcpdf();
        $pdf->SetTitle('Ventas');
        $pdf->setMargins(10, 5, 5);

        foreach ($sucursals as $sucursal) {
            $total_sucursal_c = 0;
            $total_sucursal_t = 0;
            $pdf->AddPage();
            $pdf->setPrintHeader(false);
            $pdf->setY(13);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 6, "VENTAS", 0, 1, 'C', 0, '', 0, false);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 5, $sucursal->nombre, 0, 1, 'C', 0, '', 0, false);
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->Cell(0, 5, $array_dias[date('w')] . ', ' . date('d') . ' de ' . $array_meses[date('m')] . ' de ' . date('Y'), 0, 1, 'C', 0, '', 0, false);
            $pdf->Cell(0, 5, "(Expresado en bolivianos)", 0, 1, 'C', 0, '', 0, false);
            $pdf->SetFont('helvetica', 'B', 8);
            $alto = 10;
            $font_size = 8;
            $font_size2 = 9;

            foreach ($productos as $registro) {
                $pdf->cell(190, $alto, "VENTAS DE " . $registro->nombre, 1, 1, 'C');
                $pdf->cell(15, $alto, "FECHA", 1, 0, 'C');
                $pdf->cell(25, $alto, "NRO. ORDEN", 1, 0, 'C');
                $pdf->cell(20, $alto, "PAGO", 1, 0, 'C');
                $pdf->cell(35, $alto, "CLIENTE", 1, 0, 'C');
                $pdf->cellAutoFontSize($pdf, 17.5, $alto, "CANTIDAD", $font_size, 5, 'B');
                $pdf->cellAutoFontSize($pdf, 17.5, $alto, "DESCUENTO", $font_size, 5, 'B');
                $pdf->cell(30, $alto, "SUBTOTAL", 1, 0, 'C');
                $pdf->cell(30, $alto, "TOTAL", 1, 1, 'C');
                if (count($venta_sucursals[$sucursal->id]['array_ventas'][$registro->id]) > 0) {
                    $total_c = 0;
                    $total_sub = 0;
                    $total_total = 0;
                    foreach ($venta_sucursals[$sucursal->id]['array_ventas'][$registro->id] as $value) {
                        $pdf->cellAutoFontSize($pdf, 15, $alto, date("d-m-Y", strtotime($value->venta->fecha_registro)), $font_size);
                        $pdf->cellAutoFontSize($pdf, 25, $alto, $value->venta->nro_orden, $font_size);
                        $pdf->cellAutoFontSize($pdf, 20, $alto, $value->venta->tipo_pago, $font_size);
                        $pdf->cellAutoFontSize($pdf, 35, $alto, $value->venta->cliente->nombre . ' ' . $value->venta->nit, $font_size);
                        $pdf->cellAutoFontSize($pdf, 17.5, $alto, $value->cantidad, $font_size);
                        $pdf->cellAutoFontSize($pdf, 17.5, $alto, $value->venta->descuento, $font_size);
                        $pdf->cellAutoFontSize($pdf, 30, $alto, number_format($value->subtotal, 2, '.', ','), $font_size);
                        $pdf->cellAutoFontSize($pdf, 30, $alto, number_format($value->subtotaltotal, 2, '.', ','), $font_size);
                        $pdf->Ln();
                        $total_c += (float) $value->cantidad;
                        $total_sub += (float) $value->subtotal;
                        $total_total += (float) $value->subtotaltotal;
                        // sucursal
                        $total_sucursal_c += (float) $value->cantidad;
                        $total_sucursal_t += (float) $value->subtotaltotal;
                    }

                    $pdf->setFont("helvetica", "B", $font_size2);
                    $pdf->cell(130, $alto, "TOTALES", 1, 0, 'C');
                    $pdf->setFont("helvetica", "B", $font_size);
                    $pdf->cell(30, $alto, number_format($total_sub, 2, '.', ','), 1, 0, 'C');
                    $pdf->cell(30, $alto, number_format($total_total, 2, '.', ','), 1, 1, 'C');
                } else {
                    $pdf->cell(190, $alto, "NO SE ENCONTRARON REGISTROS");
                    $pdf->Ln();
                }
            }

            $pdf->Ln();
            $pdf->Ln();
            $pdf->setFont("helvetica", "B", $font_size2);
            $pdf->cell(150, $alto, "TOTAL SUCURSAL " . $sucursal->nombre, 1, 1, 'C');
            $pdf->setFont("helvetica", "N", $font_size2);
            $pdf->cell(150, $alto, "TOTAL CANTIDAD PRODUCTOS VENDIDOS : " . $total_sucursal_c, 1, 1, 'L');
            $pdf->cell(150, $alto, "TOTAL MONTO: " .  number_format($total_sucursal_t, 2, '.', ','), 1, 1);
        }

        // Guardar PDF o forzar descarga
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="ventas.pdf"');


        // $pdf = PDF::loadView('reportes.ventas', compact('productos', 'sucursals', "venta_sucursals"))->setPaper('letter', 'portrait');

        // // ENUMERAR LAS PÁGINAS
        // $pdf->output();
        // $dom_pdf = $pdf->getDomPDF();
        // $canvas = $dom_pdf->get_canvas();
        // $alto = $canvas->get_height();
        // $ancho = $canvas->get_width();
        // $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        // return $pdf->stream('ventas.pdf');
    }

    public function g_ventas(Request $request)
    {
        $producto_id = $request->producto_id;
        $categoria_id = $request->categoria_id;
        $marca_id = $request->marca_id;
        $unidad_medida_id = $request->unidad_medida_id;
        $sucursal_id = $request->sucursal_id;
        $tipo_pago = $request->tipo_pago;
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

            if ($tipo_pago != 'todos') {
                $venta_detalles->where("ventas.tipo_pago", $tipo_pago);
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

    public function ingreso_productos()
    {
        return Inertia::render("Reportes/IngresoProductos");
    }

    public function r_ingreso_productos(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $lugar = $request->lugar;
        $producto_id = $request->producto_id;
        $categoria_id = $request->categoria_id;
        $marca_id = $request->marca_id;
        $unidad_medida_id = $request->unidad_medida_id;
        $sucursal_id = $request->sucursal_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $sucursals = [];
        if ($lugar == 'SUCURSAL') {
            $sucursals = Sucursal::select("sucursals.*");
            if ($sucursal_id != 'todos') {
                $sucursals->where("id", $sucursal_id);
            }
            $sucursals = $sucursals->get();
        }

        $pdf = new ReporteServiceTcpdf();
        $pdf->SetTitle('Ingreso de Productos');
        $pdf->setMargins(10, 5, 5);

        $ancho = 20;
        $font_size = 8;
        $font_size2 = 9;

        $html = "";
        if ($lugar == 'ALMACÉN') {
            $pdf->AddPage('L');
            $pdf->setPrintHeader(false);
            $pdf->setY(13);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 6, "INGRESO DE PRODUCTOS", 0, 1, 'C', 0, '', 0, false);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 6, "ALMACÉN", 0, 1, 'C', 0, '', 0, false);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 5, "Expedido: " . date("d/m/Y"), 0, 1, 'C', 0, '', 0, false);
            $pdf->SetFont('helvetica', 'B', 8);

            $html .= '<table border="1" cellspacing="0" style="margin-top:0px">
            <thead>
                <tr class="bg-principal">
                    <th width="4%">#</th>
                    <th width="12.55%">PRODUCTO</th>
                    <th>CATEGORÍA</th>
                    <th>MARCA</th>
                    <th>UNIDAD DE MEDIDA</th>
                    <th>PROVEEDOR</th>
                    <th>PRECIO</th>
                    <th>CANTIDAD</th>
                    <th>TIPO DE INGRESO</th>
                    <th>DESCRIPCIÓN</th>
                    <th>FECHA INGRESO</th>
                    <th>FECHA DE REGISTRO</th>
                </tr>
            </thead>
            <tbody>';
            $cont = 1;

            $ingreso_productos = IngresoProducto::select('ingreso_productos.*')
                ->join('productos', 'productos.id', '=', 'ingreso_productos.producto_id')
                ->where('lugar', 'ALMACÉN');

            if ($producto_id != 'todos') {
                $ingreso_productos->where('ingreso_productos.producto_id', $producto_id);
            }
            if ($categoria_id != 'todos') {
                $ingreso_productos->where('productos.categoria_id', $categoria_id);
            }

            if ($marca_id != 'todos') {
                $ingreso_productos->where('productos.marca_id', $marca_id);
            }

            if ($unidad_medida_id != 'todos') {
                $ingreso_productos->where('productos.unidad_medida_id', $unidad_medida_id);
            }

            if ($fecha_ini && $fecha_fin) {
                $ingreso_productos->whereBetween('ingreso_productos.fecha_ingreso', [$fecha_ini, $fecha_fin]);
            }
            $ingreso_productos = $ingreso_productos->get();

            foreach ($ingreso_productos as $ingreso_producto) {
                $html .= '<tr>';
                $html .= '<td style="font-weight:normal;" width="4%">' . $cont++ . '</td>';
                $html .= '<td style="font-weight:normal;" width="12.55%">' . $ingreso_producto->producto->nombre . '</td>';
                $html .= '<td style="font-weight:normal;">' . $ingreso_producto->producto->categoria->nombre . '</td>';
                $html .= '<td style="font-weight:normal;">' . $ingreso_producto->producto->marca->nombre . '</td>';
                $html .= '<td style="font-weight:normal;">' . $ingreso_producto->producto->unidad_medida->nombre . '</td>';
                $html .= '<td style="font-weight:normal;">' . $ingreso_producto->proveedor->razon_social . '</td>';
                $html .= '<td style="font-weight:normal;text-align:center;">' . number_format($ingreso_producto->precio, 2, '.', ',') . '</td>';
                $html .= '<td style="font-weight:normal;text-align:center;">' . $ingreso_producto->cantidad . '</td>';
                $html .= '<td style="font-weight:normal;">' . $ingreso_producto->tipo_ingreso->nombre . '</td>';
                $html .= '<td style="font-weight:normal;">' . $ingreso_producto->descripcion . '</td>';
                $html .= '<td style="font-weight:normal;">' . $ingreso_producto->fecha_ingreso_t . '</td>';
                $html .= '<td style="font-weight:normal;">' . $ingreso_producto->fecha_registro_t . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
            $pdf->writeHTML($html, true, false, true, false, '');
        } else {
            foreach ($sucursals as $sucursal) {
                $html = "";
                $pdf->AddPage('L');
                $pdf->setPrintHeader(false);
                $pdf->setY(13);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(0, 6, "INGRESO DE PRODUCTOS", 0, 1, 'C', 0, '', 0, false);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(0, 6, $sucursal->nombre, 0, 1, 'C', 0, '', 0, false);
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Cell(0, 5, "Expedido: " . date("d/m/Y"), 0, 1, 'C', 0, '', 0, false);
                $pdf->SetFont('helvetica', 'B', 8);

                $html .= '<table border="1" cellspacing="0" style="margin-top:0px">
                        <thead>
                            <tr class="bg-principal">
                                <th width="4%">#</th>
                                <th width="12.55%">PRODUCTO</th>
                                <th>CATEGORÍA</th>
                                <th>MARCA</th>
                                <th>UNIDAD DE MEDIDA</th>
                                <th>PROVEEDOR</th>
                                <th>PRECIO</th>
                                <th>CANTIDAD</th>
                                <th>TIPO DE INGRESO</th>
                                <th>DESCRIPCIÓN</th>
                                <th>FECHA INGRESO</th>
                                <th>FECHA DE REGISTRO</th>
                            </tr>
                        </thead>
                        <tbody>';

                $cont = 1;
                $sum_total_c = 0;
                $sum_total_t = 0;

                $cont = 1;

                $ingreso_productos = IngresoProducto::select('ingreso_productos.*')
                    ->join('productos', 'productos.id', '=', 'ingreso_productos.producto_id')
                    ->where('lugar', 'SUCURSAL')
                    ->where('sucursal_id', $sucursal->id);

                if (Auth::user()->tipo != 'ADMINISTRADOR') {
                    $ingreso_productos->where('origen', 'SUCURSAL');
                }
                if ($producto_id != 'todos') {
                    $ingreso_productos->where('ingreso_productos.producto_id', $producto_id);
                }
                if ($categoria_id != 'todos') {
                    $ingreso_productos->where('productos.categoria_id', $categoria_id);
                }

                if ($marca_id != 'todos') {
                    $ingreso_productos->where('productos.marca_id', $marca_id);
                }

                if ($unidad_medida_id != 'todos') {
                    $ingreso_productos->where('productos.unidad_medida_id', $unidad_medida_id);
                }

                if ($fecha_ini && $fecha_fin) {
                    $ingreso_productos->whereBetween('ingreso_productos.fecha_ingreso', [
                        $fecha_ini,
                        $fecha_fin,
                    ]);
                }
                $ingreso_productos = $ingreso_productos->get();

                if (count($ingreso_productos) > 0) {
                    foreach ($ingreso_productos as $ingreso_producto) {
                        $html .= '<tr>';
                        $html .= '<td style="font-weight:normal;" width="4%">' . $cont++ . '</td>';
                        $html .= '<td style="font-weight:normal;" width="12.55%">' . $ingreso_producto->producto->nombre . '</td>';
                        $html .= '<td style="font-weight:normal;">' . $ingreso_producto->producto->categoria->nombre . '</td>';
                        $html .= '<td style="font-weight:normal;">' . $ingreso_producto->producto->marca->nombre . '</td>';
                        $html .= '<td style="font-weight:normal;">' . $ingreso_producto->producto->unidad_medida->nombre . '</td>';
                        $html .= '<td style="font-weight:normal;">' . $ingreso_producto->proveedor->razon_social . '</td>';
                        $html .= '<td style="font-weight:normal;text-align:center;">' . number_format($ingreso_producto->precio, 2, '.', ',') . '</td>';
                        $html .= '<td style="font-weight:normal;text-align:center;">' . $ingreso_producto->cantidad . '</td>';
                        $html .= '<td style="font-weight:normal;">' . $ingreso_producto->tipo_ingreso->nombre . '</td>';
                        $html .= '<td style="font-weight:normal;">' . $ingreso_producto->descripcion . '</td>';
                        $html .= '<td style="font-weight:normal;">' . $ingreso_producto->fecha_ingreso_t . '</td>';
                        $html .= '<td style="font-weight:normal;">' . $ingreso_producto->fecha_registro_t . '</td>';
                        $html .= '</tr>';
                    }
                } else {
                    $html .= '<tr><td colspan="12">SIN REGISTROS</td></tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
                $pdf->writeHTML($html, true, false, true, false, '');
            }
        }


        // Guardar PDF o forzar descarga
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="ingresProductos.pdf"');

        // $pdf = PDF::loadView('reportes.ingreso_productos', compact('sucursals', "lugar", "producto_id", "categoria_id", "marca_id", "unidad_medida_id", "fecha_ini", "fecha_fin"))->setPaper('letter', 'landscape');

        // // ENUMERAR LAS PÁGINAS
        // $pdf->output();
        // $dom_pdf = $pdf->getDomPDF();
        // $canvas = $dom_pdf->get_canvas();
        // $alto = $canvas->get_height();
        // $ancho = $canvas->get_width();
        // $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        // return $pdf->stream('ventas.pdf');
    }

    public function salida_productos()
    {
        return Inertia::render("Reportes/SalidaProductos");
    }

    public function r_salida_productos(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $lugar = $request->lugar;
        $producto_id = $request->producto_id;
        $categoria_id = $request->categoria_id;
        $marca_id = $request->marca_id;
        $unidad_medida_id = $request->unidad_medida_id;
        $sucursal_id = $request->sucursal_id;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        $sucursals = [];
        if ($lugar == 'SUCURSAL') {
            $sucursals = Sucursal::select("sucursals.*");
            if ($sucursal_id != 'todos') {
                $sucursals->where("id", $sucursal_id);
            }
            $sucursals = $sucursals->get();
        }

        $pdf = new ReporteServiceTcpdf();
        $pdf->SetTitle('Salida de Productos');
        $pdf->setMargins(10, 5, 5);

        $ancho = 20;
        $font_size = 8;
        $font_size2 = 9;

        $html = "";
        if ($lugar == 'ALMACÉN') {
            $pdf->AddPage('L');
            $pdf->setPrintHeader(false);
            $pdf->setY(13);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 6, "SALIDA DE PRODUCTOS", 0, 1, 'C', 0, '', 0, false);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->Cell(0, 6, "ALMACÉN", 0, 1, 'C', 0, '', 0, false);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 5, "Expedido: " . date("d/m/Y"), 0, 1, 'C', 0, '', 0, false);
            $pdf->SetFont('helvetica', 'B', 8);

            $html .= '<table border="1" cellspacing="0" style="margin-top:0px">
            <thead>
                <tr class="bg-principal">
                    <th width="4%">#</th>
                    <th width="12.55%">PRODUCTO</th>
                    <th>CATEGORÍA</th>
                    <th>MARCA</th>
                    <th>UNIDAD DE MEDIDA</th>
                    <th>CANTIDAD</th>
                    <th>TIPO DE SALIDA</th>
                    <th>DESCRIPCIÓN</th>
                    <th>FECHA SALIDA</th>
                    <th>FECHA DE REGISTRO</th>
                </tr>
            </thead>
            <tbody>';
            $cont = 1;

            $salida_productos = SalidaProducto::select('salida_productos.*')
                ->join('productos', 'productos.id', '=', 'salida_productos.producto_id')
                ->where('lugar', 'ALMACÉN');

            if ($producto_id != 'todos') {
                $salida_productos->where('salida_productos.producto_id', $producto_id);
            }
            if ($categoria_id != 'todos') {
                $salida_productos->where('productos.categoria_id', $categoria_id);
            }

            if ($marca_id != 'todos') {
                $salida_productos->where('productos.marca_id', $marca_id);
            }

            if ($unidad_medida_id != 'todos') {
                $salida_productos->where('productos.unidad_medida_id', $unidad_medida_id);
            }

            if ($fecha_ini && $fecha_fin) {
                $salida_productos->whereBetween('salida_productos.fecha_salida', [$fecha_ini, $fecha_fin]);
            }
            $salida_productos = $salida_productos->get();

            foreach ($salida_productos as $salida_producto) {
                $html .= '<tr>';
                $html . '<td>' . $cont++ . '</td>';
                $html . '<td width="12.55%>' . $salida_producto->producto->nombre . '</td>';
                $html . '<td>' . $salida_producto->producto->categoria->nombre . '</td>';
                $html . '<td>' . $salida_producto->producto->marca->nombre . '</td>';
                $html . '<td>' . $salida_producto->producto->unidad_medida->nombre . '</td>';
                $html . '<td>' . $salida_producto->cantidad . '</td>';
                $html . '<td>' . $salida_producto->tipo_salida->nombre . '</td>';
                $html . '<td>' . $salida_producto->descripcion . '</td>';
                $html . '<td>' . $salida_producto->fecha_salida_t . '</td>';
                $html . '<td>' . $salida_producto->fecha_registro_t . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody>';
            $html .= '</table>';
            $pdf->writeHTML($html, true, false, true, false, '');
        } else {
            foreach ($sucursals as $sucursal) {
                $html = "";
                $pdf->AddPage('L');
                $pdf->setPrintHeader(false);
                $pdf->setY(13);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(0, 6, "SALIDA DE PRODUCTOS", 0, 1, 'C', 0, '', 0, false);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(0, 6, $sucursal->nombre, 0, 1, 'C', 0, '', 0, false);
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Cell(0, 5, "Expedido: " . date("d/m/Y"), 0, 1, 'C', 0, '', 0, false);
                $pdf->SetFont('helvetica', 'B', 8);

                $html .= '<table border="1" cellspacing="0" style="margin-top:0px">
                        <thead>
                            <tr class="bg-principal">
                                <th width="4%">#</th>
                                <th width="12.55%">PRODUCTO</th>
                                <th>CATEGORÍA</th>
                                <th>MARCA</th>
                                <th>UNIDAD DE MEDIDA</th>
                                <th>CANTIDAD</th>
                                <th>TIPO DE SALIDA</th>
                                <th>DESCRIPCIÓN</th>
                                <th>FECHA SALIDA</th>
                                <th>FECHA DE REGISTRO</th>
                            </tr>
                        </thead>
                        <tbody>';

                $cont = 1;
                $sum_total_c = 0;
                $sum_total_t = 0;

                $cont = 1;

                $salida_productos = SalidaProducto::select('salida_productos.*')
                    ->join('productos', 'productos.id', '=', 'salida_productos.producto_id')
                    ->where('lugar', 'SUCURSAL')
                    ->where('sucursal_id', $sucursal->id);

                if (Auth::user()->tipo != 'ADMINISTRADOR') {
                    $salida_productos->where('origen', 'SUCURSAL');
                }
                if ($producto_id != 'todos') {
                    $salida_productos->where('salida_productos.producto_id', $producto_id);
                }
                if ($categoria_id != 'todos') {
                    $salida_productos->where('productos.categoria_id', $categoria_id);
                }

                if ($marca_id != 'todos') {
                    $salida_productos->where('productos.marca_id', $marca_id);
                }

                if ($unidad_medida_id != 'todos') {
                    $salida_productos->where('productos.unidad_medida_id', $unidad_medida_id);
                }

                if ($fecha_ini && $fecha_fin) {
                    $salida_productos->whereBetween('salida_productos.fecha_salida', [$fecha_ini, $fecha_fin]);
                }
                $salida_productos = $salida_productos->get();

                if (count($salida_productos) > 0) {
                    foreach ($salida_productos as $salida_producto) {
                        $html .= '<tr>';
                        $html .= '<td>' . $cont++ . '</td>';
                        $html .= '<td>' . $salida_producto->producto->nombre . '</td>';
                        $html .= '<td>' . $salida_producto->producto->categoria->nombre . '</td>';
                        $html .= '<td>' . $salida_producto->producto->marca->nombre . '</td>';
                        $html .= '<td>' . $salida_producto->producto->unidad_medida->nombre . '</td>';
                        $html .= '<td class="centreado">' . $salida_producto->cantidad . '</td>';
                        $html .= '<td>' . $salida_producto->tipo_salida->nombre . '</td>';
                        $html .= '<td>' . $salida_producto->descripcion . '</td>';
                        $html .= '<td>' . $salida_producto->fecha_salida_t . '</td>';
                        $html .= '<td>' . $salida_producto->fecha_registro_t . '</td>';
                        $html .= '</tr>';
                    }
                } else {
                    $html .= '<tr><td colspan="10">SIN REGISTROS</td></tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
                $pdf->writeHTML($html, true, false, true, false, '');
            }
        }


        // Guardar PDF o forzar descarga
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="salidaProductos.pdf"');

        // $pdf = PDF::loadView('reportes.salida_productos', compact('sucursals', "lugar", "producto_id", "categoria_id", "marca_id", "unidad_medida_id", "fecha_ini", "fecha_fin"))->setPaper('letter', 'landscape');

        // // ENUMERAR LAS PÁGINAS
        // $pdf->output();
        // $dom_pdf = $pdf->getDomPDF();
        // $canvas = $dom_pdf->get_canvas();
        // $alto = $canvas->get_height();
        // $ancho = $canvas->get_width();
        // $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        // return $pdf->stream('salidas.pdf');
    }

    public function productos()
    {
        return Inertia::render("Reportes/Productos");
    }

    public function r_productos(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        $producto_id = $request->producto_id;
        $categoria_id = $request->categoria_id;
        $marca_id = $request->marca_id;
        $unidad_medida_id = $request->unidad_medida_id;

        $productos = Producto::select(
            "productos.id",
            "productos.nombre",
            "productos.precio",
            "productos.categoria_id",
            "productos.marca_id",
            "productos.unidad_medida_id",
            "productos.precio",
            "productos.stock_min",
            "productos.fecha_registro",
        )
            ->with([
                "categoria:id,nombre",
                "marca:id,nombre",
                "unidad_medida:id,nombre",
                "almacen_producto"
            ]);
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


        $pdf = new ReporteServiceTcpdf();
        $pdf->SetTitle('Productos');
        $pdf->setMargins(10, 5, 5);
        $pdf->AddPage();
        $pdf->setPrintHeader(false);
        $pdf->setY(13);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 6, "LISTA DE PRODUCTOS", 0, 1, 'C', 0, '', 0, false);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 5, "Expedido: " . date("d/m/Y"), 0, 1, 'C', 0, '', 0, false);
        $pdf->SetFont('helvetica', 'B', 8);
        $alto = 10;
        $ancho = 20;
        $font_size = 8;
        $font_size2 = 9;

        $pdf->SetFont('helvetica', 'B', $font_size2);
        $pdf->Cell(9, $alto, "#", 1, 0, 'C', 0, '', 0, false);
        $pdf->Cell(40, $alto, "NOMBRE", 1, 0, 'C', 0, '', 0, false);
        $pdf->Cell(30, $alto, "CATEGORÍA", 1, 0, 'C', 0, '', 0, false);
        $pdf->Cell(30, $alto, "MARCA", 1, 0, 'C', 0, '', 0, false);
        $pdf->cellAutoFontSize($pdf, 30, $alto, "UNIDAD MEDIDA", $font_size, $font_size, 'B');
        $pdf->Cell(20, $alto, "PRECIO", 1, 0, 'C', 0, '', 0, false);
        $pdf->cellAutoFontSize($pdf, 15, $alto, "STOCK MIN.", $font_size, 5, 'B');
        $pdf->Cell(25, $alto, "FECHA DE REGISTRO", 1, 1, 'C', 0, '', 0, false);
        $cont = 1;

        $pdf->SetFont('helvetica', 'N', $font_size);
        foreach ($productos as $producto) {

            $pdf->cellAutoFontSize($pdf, 9, $alto, $cont++, $font_size);

            $pdf->cellAutoFontSize(
                $pdf,
                40,
                $alto,
                $producto->nombre,
                $font_size
            );

            $pdf->cellAutoFontSize(
                $pdf,
                30,
                $alto,
                $producto->categoria->nombre,
                $font_size
            );


            $pdf->cellAutoFontSize(
                $pdf,
                30,
                $alto,
                $producto->marca->nombre,
                $font_size
            );

            $pdf->cellAutoFontSize(
                $pdf,
                30,
                $alto,
                $producto->unidad_medida->nombre,
                $font_size
            );

            $pdf->Cell(
                20,
                $alto,
                $producto->precio,
                1,
                0,
                'C',
                0,
                '',
                0,
                false
            );

            $pdf->Cell(
                15,
                $alto,
                $producto->stock_min,
                1,
                0,
                'C',
                0,
                '',
                0,
                false
            );

            $pdf->Cell(
                25,
                $alto,
                $producto->fecha_registro,
                1,
                1,
                'C',
                0,
                '',
                0,
                false
            );
        }
        // Guardar PDF o forzar descarga
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="productos.pdf"');

        // $pdf = PDF::loadView('reportes.productos', compact('productos'))->setPaper('letter', 'portrait');

        // // ENUMERAR LAS PÁGINAS
        // $pdf->output();
        // $dom_pdf = $pdf->getDomPDF();
        // $canvas = $dom_pdf->get_canvas();
        // $alto = $canvas->get_height();
        // $ancho = $canvas->get_width();
        // $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        // return $pdf->stream('ventas.pdf');
    }
}
