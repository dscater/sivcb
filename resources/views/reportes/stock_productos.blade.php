<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>StockProductos</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 0.5cm;
            margin-bottom: 0.5cm;
            margin-left: 1.5cm;
            margin-right: 0.5cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 20px;
            page-break-before: avoid;
        }

        table thead tr th,
        tbody tr td {
            padding: 3px;
            word-wrap: break-word;
        }

        table thead tr th {
            font-size: 7pt;
        }

        table tbody tr td {
            font-size: 6pt;
        }


        .encabezado {
            width: 100%;
        }

        .logo img {
            position: absolute;
            height: 100px;
            top: -20px;
            left: 0px;
        }

        h2.titulo {
            width: 450px;
            margin: auto;
            margin-top: 0PX;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14pt;
        }

        .texto {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: bold;
            font-size: 1.1em;
        }

        .fecha {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: normal;
            font-size: 0.85em;
        }

        .total {
            text-align: right;
            padding-right: 15px;
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        table thead {
            background: rgb(236, 236, 236)
        }

        tr {
            page-break-inside: avoid !important;
        }

        .centreado {
            padding-left: 0px;
            text-align: center;
        }

        .datos {
            margin-left: 15px;
            border-top: solid 1px;
            border-collapse: collapse;
            width: 250px;
        }

        .txt {
            font-weight: bold;
            text-align: right;
            padding-right: 5px;
        }

        .txt_center {
            font-weight: bold;
            text-align: center;
        }

        .cumplimiento {
            position: absolute;
            width: 150px;
            right: 0px;
            top: 86px;
        }

        .b_top {
            border-top: solid 1px black;
        }

        .gray {
            background: rgb(202, 202, 202);
        }

        .bg-principal {
            background: #153f59;
            color: white;
        }

        .derecha {
            text-align: right;
        }

        .text-md {
            font-size: 9pt;
        }

        .bold {
            font-weight: bold;
        }

        .img_celda img {
            width: 45px;
        }

        .title_lugar {
            font-size: 9pt;
            margin-bottom: 0px;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    <div class="encabezado">
        <div class="logo">
            <img src="{{ $configuracion->first()->logo_b64 }}">
        </div>
        <h2 class="titulo">
            {{ $configuracion->first()->razon_social }}
        </h2>
        <h4 class="texto">STOCK DE PRODUCTOS</h4>
        <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
    </div>

    @if ($lugar == 'ALMACÉN')
        <h4 class="title_lugar">STOCK DE ALMACÉN</h4>
        <table border="1" style="margin-top:0px">
            <thead>
                <tr class="bg-principal">
                    <th width="4%">#</th>
                    <th>PRODUCTO</th>
                    <th>CATEGORÍA</th>
                    <th>MARCA</th>
                    <th>UNIDAD DE MEDIDA</th>
                    <th>PRECIO</th>
                    <th>STOCK ACTUAL</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                        $sum_total_c = 0;
                        $sum_total_t = 0;
                @endphp
                @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $cont++ }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->categoria->nombre }}</td>
                        <td>{{ $producto->marca->nombre }}</td>
                        <td>{{ $producto->unidad_medida->nombre }}</td>
                        <td>{{ $producto->precio }}</td>
                        <td class="centreado">
                            {{ $producto->almacen_producto ? $producto->almacen_producto->stock_actual : 0 }}</td>
                        @php
                            $total =
                                (float) $producto->precio * ($producto->almacen_producto ? $producto->almacen_producto->stock_actual : 0);
                            $sum_total_c += (float) ($producto->almacen_producto ? $producto->almacen_producto->stock_actual : 0);
                            $sum_total_t += (float) $total;
                        @endphp
                        <td class="centreado">{{ $total }}</td>
                    </tr>
                @endforeach
                <tr class="bg-principal">
                    <td colspan="6" class="derecha bold text-right text-md">TOTALES</td>
                    <td class="bold centreado text-md">{{ $sum_total_c }}</td>
                    <td class="bold centreado text-md">{{ $sum_total_t }}</td>
                </tr>
            </tbody>
        </table>
    @else
        @foreach ($sucursals as $sucursal)
            <h4 class="title_lugar">{{ $sucursal->nombre }}</h4>
            <table border="1" style="margin-top:0px">
                <thead>
                    <tr class="bg-principal">
                        <th width="4%">#</th>
                        <th>PRODUCTO</th>
                        <th>CATEGORÍA</th>
                        <th>MARCA</th>
                        <th>UNIDAD DE MEDIDA</th>
                        <th>PRECIO</th>
                        <th>STOCK ACTUAL</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 1;
                        $sum_total_c = 0;
                        $sum_total_t = 0;
                    @endphp
                    @foreach ($productos as $producto)
                        @php
                            $sucursal_producto = App\Models\SucursalProducto::where('producto_id', $producto->id)
                                ->where('sucursal_id', $sucursal->id)
                                ->get()
                                ->first();
                        @endphp
                        <tr>
                            <td>{{ $cont++ }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->categoria->nombre }}</td>
                            <td>{{ $producto->marca->nombre }}</td>
                            <td>{{ $producto->unidad_medida->nombre }}</td>
                            <td class="centreado">{{ $producto->precio }}</td>
                            <td class="centreado">
                                {{ $sucursal_producto ? $sucursal_producto->stock_actual : 0 }}</td>
                            @php
                                $total =
                                    (float) $producto->precio *
                                    ($sucursal_producto ? $sucursal_producto->stock_actual : 0);
                                $sum_total_c += (float) ($sucursal_producto ? $sucursal_producto->stock_actual : 0);
                                $sum_total_t += (float) $total;
                            @endphp
                            <td class="centreado">{{ $total }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-principal">
                        <td colspan="6" class="derecha bold text-right text-md">TOTALES</td>
                        <td class="bold centreado text-md">{{ $sum_total_c }}</td>
                        <td class="bold centreado text-md">{{ $sum_total_t }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @endif
</body>

</html>
