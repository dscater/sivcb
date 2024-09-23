<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Planilla de Pagos</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 1.5cm;
            margin-right: 1cm;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 20px;
            margin: auto;
            page-break-before: avoid;
        }

        table thead tr th,
        tbody tr td {
            padding: 3px;
            word-wrap: break-word;
        }

        table thead tr th {
            font-size: 8.5pt;
        }

        table tbody tr td {
            font-size: 7.5pt;
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
            background: #1867C0;
            color: white;
        }

        .txt_rojo {}

        .img_celda img {
            width: 45px;
        }

        .info_cliente {
            margin-left: 50px;
            font-size: 9pt;
        }

        .page-break {
            page-break-after: always;
        }

        td.cancelado {
            font-weight: bold;
            color: rgb(0, 192, 0);
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @php
        $cont_cliente = 0;
    @endphp
    @foreach ($clientes as $cliente)
        <div class="encabezado">
            <div class="logo">
                <img src="{{ $configuracion->first()->logo_b64 }}">
            </div>
            <h2 class="titulo">
                {{ $configuracion->first()->razon_social }}
            </h2>
            <h4 class="texto">PLANILLA DE PAGOS</h4>
            <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
        </div>
        @php
            $venta_lotes = App\Models\VentaLote::where('cliente_id', $cliente->id)->get();
            if (isset($venta_lote_id) && $venta_lote_id != 'todos') {
                $venta_lotes = App\Models\VentaLote::where('id', $venta_lote_id)->get();
            }
        @endphp
        @foreach ($venta_lotes as $venta_lote)
            <p class="info_cliente"><strong>Cliente:</strong> {{ $cliente->user->full_name }}</p>
            <p class="info_cliente"><strong>C.I.:</strong> {{ $cliente->user->full_ci }}</p>

            @php
                $contador = 0;
            @endphp
            <p class="info_cliente"><strong>Urbanización/Manzano/Lote:</strong>
                {{ $venta_lote->urbanizacion->nombre }}/{{ $venta_lote->manzano->nombre }}/{{ $venta_lote->lote->nombre }}
            </p>
            <p class="info_cliente"><strong>Fecha de Formalización:</strong>
                {{ $venta_lote->fecha_formalizacion_t }}
            </p>
            @php
                $venta_planillas = App\Models\VentaPlanilla::where('venta_lote_id', $venta_lote->id)->get();
            @endphp
            <table border="1">
                <thead>
                    <tr>
                        <th width="8%">NRO. CUOTA</th>
                        <th>CUOTA</th>
                        <th>ESTADO PAGO</th>
                        <th>FECHA PAGO</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 1;
                    @endphp
                    @foreach ($venta_planillas as $venta_planilla)
                        <tr>
                            <td class="centreado">{{ $venta_planilla->nro_cuota }}</td>
                            <td class="centreado">{{ $venta_planilla->cuota }}</td>
                            <td class="centreado {{ $venta_planilla->estado == 1 ? 'cancelado' : 'pendiente' }}">
                                {{ $venta_planilla->estado == 1 ? 'CANCELADO' : 'PENDIENTE' }}</td>
                            <td class="centreado">{{ $venta_planilla->fecha_pago_t }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @php
                $contador++;
            @endphp
            @if ($contador < count($venta_lotes))
                <div class="page-break"></div>
            @endif
        @endforeach
        @php
            $cont_cliente++;
        @endphp
        @if ($cont_cliente < count($clientes))
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
