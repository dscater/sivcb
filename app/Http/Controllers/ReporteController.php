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
        $ancho = 20;
        $font_size = 8;
        $font_size2 = 9;
        if ($lugar == 'ALMACÉN') {
            $html = '<h3 style="font-weight: bold; margin-bottom: 3px;">STOCK DE ALMACÉN</h3>';
            $html .= '<table border="1" cellspacing="0" style="margin-top:0px">
            <thead>
                <tr class="bg-principal">
                    <th width="4%">#</th>
                    <th width="20%">PRODUCTO</th>
                    <th>CATEGORÍA</th>
                    <th>MARCA</th>
                    <th>UNIDAD DE MEDIDA</th>
                    <th>PRECIO</th>
                    <th>STOCK ACTUAL</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>';
            $cont = 1;
            $sum_total_c = 0;
            $sum_total_t = 0;
            $pdf->SetFont('helvetica', 'N', $font_size);
            foreach ($productos as $producto) {
                $html .= '<tr>';
                $html .= '<td width="4%">' . $cont++ . '</td>';
                $html .= '<td width="20%">' . $producto->nombre . '</td>';
                $html .= '<td>' . $producto->categoria->nombre . '</td>';
                $html .= '<td>' . $producto->marca->nombre . '</td>';
                $html .= '<td>' . $producto->unidad_medida->nombre . '</td>';
                $html .= '<td>' . $producto->precio . '</td>';
                $html .= '<td class="centreado">' . ($producto->almacen_producto ? $producto->almacen_producto->stock_actual : 0) . '</td>';
                $total = (float) $producto->precio * ($producto->almacen_producto ? $producto->almacen_producto->stock_actual : 0);
                $sum_total_c += (float) ($producto->almacen_producto ? $producto->almacen_producto->stock_actual : 0);
                $sum_total_t += (float) $total;
                $html .= '<td class="centreado">' . $total . '</td>';
                $html .= '</tr>';
            }

            $html .= '<tr class="bg-principal">';
            $html .= '<td colspan="6" class="derecha bold text-right text-md">TOTALES</td>';
            $html .= '<td class="bold centreado text-md">' . $sum_total_c . '</td>';
            $html .= '<td class="bold centreado text-md">' . $sum_total_t . '</td>';
            $html .= '</tr>';

            $html .= '</tbody>';
            $html .= '</table>';
        } else {
            $html = '';
            foreach ($sucursals as $sucursal) {
                $html .= '<h3 style="font-weight: bold; margin-bottom: 3px;">' . $sucursal->nombre . '</h3>';
                $html .= '<table border="1" cellspacing="0" style="margin-top:0px">
                        <thead>
                            <tr class="bg-principal">
                                <th width="4%">#</th>
                                <th width="20%">PRODUCTO</th>
                                <th>CATEGORÍA</th>
                                <th>MARCA</th>
                                <th>UNIDAD DE MEDIDA</th>
                                <th>PRECIO</th>
                                <th>STOCK ACTUAL</th>
                                <th>TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>';

                $cont = 1;
                $sum_total_c = 0;
                $sum_total_t = 0;

                foreach ($productos as $producto) {
                    $sucursal_producto = SucursalProducto::where('producto_id', $producto->id)
                        ->where('sucursal_id', $sucursal->id)
                        ->get()
                        ->first();

                    $html .= '<tr>';
                    $html .= '<td width="4%" style="font-weight:normal;">' . $cont++ . '</td>';
                    $html .= '<td width="20%" style="font-weight:normal;">' . $producto->nombre . '</td>';
                    $html .= '<td style="font-weight:normal;">' . $producto->categoria->nombre . '</td>';
                    $html .= '<td style="font-weight:normal;">' . $producto->marca->nombre . '</td>';
                    $html .= '<td style="font-weight:normal;">' . $producto->unidad_medida->nombre . '</td>';
                    $html .= '<td style="text-align:center;font-weight:normal;">' . $producto->precio . '</td>';
                    $html .= '<td style="text-align:center;font-weight:normal;">' . ($sucursal_producto ? $sucursal_producto->stock_actual : 0) . '</td>';
                    $total =
                        (float) $producto->precio *
                        ($sucursal_producto ? $sucursal_producto->stock_actual : 0);
                    $sum_total_c += (float) ($sucursal_producto ? $sucursal_producto->stock_actual : 0);
                    $sum_total_t += (float) $total;
                    $html .= '<td style="text-align:center;font-weight:normal;">' . $total . '</td>';
                    $html .= '</tr>';
                }
                $html .= '<tr class="bg-principal">';
                $html .= '<td colspan="6" class="derecha bold text-right text-md">TOTALES</td>';
                $html .= '<td style="text-align:center">' . $sum_total_c . '</td>';
                $html .= '<td style="text-align:center">' . $sum_total_t . '</td>';
                $html .= '</tr>';

                $html .= '</tbody>';
                $html .= '</table>';
            }
        }

        $pdf->writeHTML($html, true, false, true, false, '');

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
            $ancho = 20;
            $font_size = 8;
            $font_size2 = 9;

            $html = '';

            foreach ($productos as $registro) {
                $html .= '<br><br><table border="1" cellpadding="1" cellspacing="0">
                <thead>
                    <tr>
                        <td style="font-size:10pt;text-align:center" colspan="9"><strong>' . $registro->nombre . '</strong></td>
                    </tr>
                    <tr>
                        <th rowspan="2">FECHA</th>
                        <th rowspan="2">DETALLE</th>
                        <th colspan="3">CANTIDADES</th>
                        <th rowspan="2">P/U</th>
                        <th colspan="3">BOLIVIANOS</th>
                    </tr>
                    <tr>
                        <th>ENTRADA</th>
                        <th>SALIDA</th>
                        <th>SALDO</th>
                        <th>ENTRADA</th>
                        <th>SALIDA</th>
                        <th>SALDO</th>
                    </tr>
                </thead>
                <tbody>';

                if (count($kardex_sucursals[$sucursal->id]['array_kardex'][$registro->id]) > 0 || $kardex_sucursals[$sucursal->id]['array_saldo_anterior'][$registro->id]['sw']) {

                    $total = 0;
                    if ($kardex_sucursals[$sucursal->id]['array_saldo_anterior'][$registro->id]['sw']) {
                        $html .= '<tr>';
                        $html .= '<td style="font-weight:normal;"></td>';
                        $html .= '<td style="font-weight:normal;">SALDO ANTERIOR</td>';
                        $html .= '<td style="font-weight:normal;"></td>';
                        $html .= '<td style="font-weight:normal;"></td>';
                        $html .= '<td style="text-align:center; font-weight:normal;">' . $kardex_sucursals[$sucursal->id]['array_saldo_anterior'][$registro->id]['saldo_anterior']['cantidad_saldo'] . '</td>';
                        $html .= '<td style="text-align:center; font-weight:normal;">' . $registro->precio . '</td>';
                        $html .= '<td style="font-weight:normal;"></td>';
                        $html .= '<td style="font-weight:normal;"></td>';
                        $html .= '<td style="text-align:center; font-weight:normal;">' . number_format($kardex_sucursals[$sucursal->id]['array_saldo_anterior'][$registro->id]['saldo_anterior']['monto_saldo'], 2, '.', ',') . '</td>';
                        $html .= '</tr>';
                    }
                    foreach ($kardex_sucursals[$sucursal->id]['array_kardex'][$registro->id] as $value) {
                        $html .= '<tr>';
                        $html .= '<td style="font-weight:normal;">' . date('d-m-Y', strtotime($value['fecha'])) . '</td>';
                        $html .= '<td style="font-weight:normal;">' . $value['detalle'] . '</td>';
                        $html .= '<td style="text-align:center; font-weight:normal;">' . $value['cantidad_ingreso'] . '</td>';
                        $html .= '<td style="text-align:center; font-weight:normal;">' . $value['cantidad_salida'] . '</td>';
                        $html .= '<td style="text-align:center; font-weight:normal;">' . $value['cantidad_saldo'] . '</td>';
                        $html .= '<td style="text-align:center; font-weight:normal;">' . number_format($value['cu'], 2, '.', ',') . '</td>';
                        $html .= '<td style="text-align:center; font-weight:normal;">' . $value['monto_ingreso'] . '</td>';
                        $html .= '<td style="text-align:center; font-weight:normal;">' . $value['monto_salida'] . '</td>';
                        $html .= '<td style="text-align:center; font-weight:normal;">' . number_format($value['monto_saldo'], 2, '.', ',') . '</td>';
                        $html .= '</tr>';
                    }
                } else {
                    $html .= '<tr>
                            <td colspan="9" class="centreado">NO SE ENCONTRARON REGISTROS</td>
                        </tr>';
                }
                $html .= '</tbody>';
                $html .= '</table>';
            }
            $pdf->writeHTML($html, true, false, true, false, '');
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
            $ancho = 20;
            $font_size = 8;
            $font_size2 = 9;

            $html = '';

            foreach ($productos as $registro) {
                $html .= '<br><br>';
                $html .= '<table border="1" cellpadding="1">
                <thead>
                    <tr>
                        <td style="font-size:10pt;text-align:center;" colspan="8"><strong>VENTAS DE ' . $registro->nombre . '</strong></td>
                    </tr>
                    <tr>
                        <th>FECHA</th>
                        <th>NRO. ORDEN</th>
                        <th>PAGO</th>
                        <th>CLIENTE</th>
                        <th>CANTIDAD</th>
                        <th>DESCUENTO (1-100%)</th>
                        <th>SUBTOTAL</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>';
                if (count($venta_sucursals[$sucursal->id]['array_ventas'][$registro->id]) > 0) {
                    $total_c = 0;
                    $total_sub = 0;
                    $total_total = 0;
                    foreach ($venta_sucursals[$sucursal->id]['array_ventas'][$registro->id] as $value) {
                        $html .= '<tr>';
                        $html .= '<td style="font-weight:normal;">' . date('d-m-Y', strtotime($value->venta->fecha_registro_t)) . '</td>';
                        $html .= '<td style="font-weight:normal;">' . $value->venta->nro_orden . '</td>';
                        $html .= '<td style="font-weight:normal;">' . $value->venta->tipo_pago . '</td>';
                        $html .= '<td style="font-weight:normal;">' . $value->venta->cliente->nombre . '<br />' . $value->venta->nit . '</td>';
                        $html .= '<td style="font-weight:normal;text-align:center;">' . $value->cantidad . '</td>';
                        $html .= '<td style="font-weight:normal;text-align:center;">' . $value->venta->descuento . '</td>';
                        $html .= '<td style="font-weight:normal;text-align:center;">' . number_format($value->subtotal, 2, '.', ',') . '</td>';
                        $html .= '<td style="font-weight:normal;text-align:center;">' . number_format($value->subtotaltotal, 2, '.', ',') . '</td>';
                        $total_c += (float) $value->cantidad;
                        $total_sub += (float) $value->subtotal;
                        $total_total += (float) $value->subtotaltotal;
                        // sucursal
                        $total_sucursal_c += (float) $value->cantidad;
                        $total_sucursal_t += (float) $value->subtotaltotal;
                        $html .= '</tr>';
                    }
                    $html .= '<tr class="bg-principal">';
                    $html .= '<td colspan="4" class="bold derecha text-md">TOTALES</td>';
                    $html .= '<td class="centreado bold text-md">' . $total_c . '</td>';
                    $html .= '<td></td>';
                    $html .= '<td class="centreado bold text-md">' . number_format($total_sub, 2, '.', ',') . '</td>';
                    $html .= '<td class="centreado bold text-md">' . number_format($total_total, 2, '.', ',') . '</td>';
                    $html .= '</tr>';
                } else {
                    $html .= '<tr>
                            <td colspan="8">NO SE ENCONTRARON REGISTROS</td>
                        </tr>';
                }
                $html .= '</tbody>
            </table>';
            }

            $html .= '<br/><br/><table border="1" style="width:60%;">
            <tbody>';
            $html .= '<tr class="bg-principal">';
            $html .= '<td class="bold">TOTAL SUCURSAL ' . $sucursal->nombre . '</td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td class="bold">TOTAL CANTIDAD PRODUCTOS VENDIDOS: ' . $total_sucursal_c . '</td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td class="bold">TOTAL MONTO: ' . number_format($total_sucursal_t, 2, '.', ',') . '</td>';
            $html .= '</tr>
            </tbody>
        </table>';

            $pdf->writeHTML($html, true, false, true, false, '');
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
        $ancho = 20;
        $font_size = 8;
        $font_size2 = 9;

        $html = '<br><br/><table border="1">
        <thead>
            <tr class="bg-principal">
                <th width="4%">#</th>
                <th>NOMBRE</th>
                <th>CATEGORÍA</th>
                <th>MARCA</th>
                <th>UNIDAD DE MEDIDA</th>
                <th>PRECIO</th>
                <th>STOCK MIN.</th>
                <th>IMAGEN</th>
                <th>FECHA DE REGISTRO</th>
            </tr>
        </thead>
        <tbody>';

        $cont = 1;
        foreach ($productos as $producto) {
            $html .= '<tr>';
            $html .= '<td width="4%">' . $cont++ . '</td>';
            $html .= '<td style="font-weight:normal;">' . $producto->nombre . '</td>';
            $html .= '<td style="font-weight:normal;">' . $producto->categoria->nombre . '</td>';
            $html .= '<td style="font-weight:normal;">' . $producto->marca->nombre . '</td>';
            $html .= '<td style="font-weight:normal;">' . $producto->unidad_medida->nombre . '</td>';
            $html .= '<td style="font-weight:normal;">' . number_format($producto->precio, 2, '.', ',') . '</td>';
            $html .= '<td style="font-weight:normal;">' . $producto->stock_min . '</td>';
            $html .= '<td style="text-align:center;"><img src="' . $producto->foto_b64 . '" alt="Imagen" width="30px"></td>';
            $html .= '<td style="font-weight:normal;">' . $producto->fecha_registro_t . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');

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
