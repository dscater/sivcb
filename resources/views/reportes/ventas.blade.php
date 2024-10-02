<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ventas</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 0.5cm;
            margin-bottom: 0.5cm;
            margin-left: 1.5cm;
            margin-right: 0.5cm;
            border: 5px solid blue;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 0px;
            page-break-before: avoid;
        }

        table thead {
            page-break-before: always;
        }

        table thead tr th,
        tbody tr td {
            font-size: 0.63em;
        }

        .encabezado {
            width: 100%;
        }

        .logo img {
            position: absolute;
            height: 90px;
            top: 0px;
            left: 0px;
        }

        h2.titulo {
            width: 450px;
            margin: auto;
            margin-top: 15px;
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
            background: #153f59;
            color: white;
        }

        .bg-principal {
            background: #153f59;
            color: white;
        }

        table thead tr th {
            padding: 3px;
            font-size: 0.7em;
        }

        table tbody tr td {
            padding: 3px;
            font-size: 0.7em;
        }

        tr {
            page-break-inside: avoid !important;
        }

        table tbody tr td.franco {
            background: red;
            color: white;
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

        .p_cump {
            color: red;
            font-size: 1.2em;
        }

        .b_top {
            border-top: solid 1px black;
        }

        .gray {
            background: rgb(202, 202, 202);
        }

        .derecha {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .text-md {
            font-size: 10pt;
        }

        .img_celda img {
            width: 45px;
        }

        .producto {
            margin-bottom: -2px;
        }

        .producto tbody tr td {
            font-size: 0.8em;
            font-weight: bold;
            background: rgb(228, 228, 228);
        }

        .nueva_pagina {
            page-break-after: always;
        }

        .table_info_final {
            margin: auto;
            margin-top: 20px;
            width: 60%;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @php
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
    @endphp

    @foreach ($sucursals as $sucursal)
        @php
            $total_sucursal_c = 0;
            $total_sucursal_t = 0;
        @endphp
        <div class="encabezado">
            <div class="logo">
                <img src="{{ $configuracion->first()->logo_b64 }}">
            </div>
            <h2 class="titulo">
                {{ App\Models\Configuracion::first()->razon_social }}
            </h2>
            <h4 class="texto">VENTAS</h4>
            <h4 class="texto">{{ $sucursal->nombre }}</h4>
            <h4 class="fecha">{{ $array_dias[date('w')] }}, {{ date('d') }} de
                {{ $array_meses[date('m')] }} de {{ date('Y') }}</h4>
            <h4 class="fecha">(Expresado en bolivianos)</h4>
        </div>
        @foreach ($productos as $registro)
            <br><br>
            <table border="1">
                <thead>
                    <tr>
                        <td class="centreado" colspan="7"><strong>VENTAS DE {{ $registro->nombre }}</strong></td>
                    </tr>
                    <tr>
                        <th width="9%">FECHA</th>
                        <th>NRO. ORDEN</th>
                        <th>CLIENTE</th>
                        <th>CANTIDAD</th>
                        <th>DESCUENTO (1-100%)</th>
                        <th>SUBTOTAL</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($venta_sucursals[$sucursal->id]['array_ventas'][$registro->id]) > 0)
                        @php
                            $total_c = 0;
                            $total_sub = 0;
                            $total_total = 0;
                        @endphp
                        @foreach ($venta_sucursals[$sucursal->id]['array_ventas'][$registro->id] as $value)
                            <tr>
                                <td>{{ date('d-m-Y', strtotime($value->venta->fecha_registro_t)) }}</td>
                                <td>{{ $value->venta->nro_orden }}</td>
                                <td>{{ $value->venta->cliente->nombre }}<br />{{ $value->venta->nit }}</td>
                                <td class="centreado">{{ $value->cantidad }}</td>
                                <td class="centreado">{{ $value->venta->descuento }}</td>
                                <td class="centreado">{{ number_format($value->subtotal, 2, '.', ',') }}</td>
                                <td class="centreado">{{ number_format($value->subtotaltotal, 2, '.', ',') }}</td>
                                @php
                                    $total_c += (float) $value->cantidad;
                                    $total_sub += (float) $value->subtotal;
                                    $total_total += (float) $value->subtotaltotal;
                                    // sucursal
                                    $total_sucursal_c += (float) $value->cantidad;
                                    $total_sucursal_t += (float) $value->subtotaltotal;
                                @endphp
                            </tr>
                        @endforeach
                        <tr class="bg-principal">
                            <td colspan="3" class="bold derecha text-md">TOTALES</td>
                            <td class="centreado bold text-md">{{ $total_c }}</td>
                            <td></td>
                            <td class="centreado bold text-md">{{ number_format($total_sub, 2, '.', ',') }}</td>
                            <td class="centreado bold text-md">{{ number_format($total_total, 2, '.', ',') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="7" class="centreado">NO SE ENCONTRARON REGISTROS</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach

        <table border="1" class="table_info_final">
            <tbody>
                <tr class="bg-principal">
                    <td class="bold">TOTAL SUCURSAL {{ $sucursal->nombre }}</td>
                </tr>
                <tr>
                    <td class="bold">TOTAL CANTIDAD PRODUCTOS VENDIDOS: {{ $total_sucursal_c }}</td>
                </tr>
                <tr>
                    <td class="bold">TOTAL MONTO: {{ number_format($total_sucursal_t, 2, '.', ',') }}</td>
                </tr>
            </tbody>
        </table>

        @php
            $contador_su++;
        @endphp

        @if ($contador_su < count($sucursals))
            <div class="nueva_pagina"></div>
        @endif
    @endforeach
</body>

</html>
