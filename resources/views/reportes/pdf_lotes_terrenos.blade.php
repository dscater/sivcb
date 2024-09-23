<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>LotesTerrenos</title>
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
            width: 80%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 20px;
            page-break-before: avoid;
            margin: auto;
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

        .derecha {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .img_celda img {
            width: 45px;
        }

        .page-break {
            page-break-after: always;
        }

        td.disponible {
            background: #06bb7f;
        }

        td.vendido {
            background: #e44a36;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @php
        $contador = 0;
    @endphp
    @foreach ($urbanizacions as $urbanizacion)
        <div class="encabezado">
            <div class="logo">
                <img src="{{ $configuracion->first()->logo_b64 }}">
            </div>
            <h2 class="titulo">
                {{ $configuracion->first()->razon_social }}
            </h2>
            <h4 class="texto">LOTES DE TERRENOS DD</h4>
            <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
        </div>

        <h4 style="margin-left:70px;">{{ $urbanizacion->nombre }}</h4>
        @php
            $lotes = App\Models\Lote::where('urbanizacion_id', $urbanizacion->id)->get();
        @endphp
        <table border="1">
            <thead>
                <tr>
                    <th width="3%">N°</th>
                    <th>MANZANO/LOTE</th>
                    <th width="12%">DISPONIBLE</th>
                    <th width="12%">OCUPADO</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                    $disponibles = 0;
                    $ocupados = 0;
                @endphp
                @forelse ($lotes as $lote)
                    <tr>
                        <td>{{ $cont++ }}</td>
                        <td>{{ $lote->manzano->nombre }}/{{ $lote->nombre }}</td>
                        @if ($lote->vendido == 1)
                            <td></td>
                            <td class="vendido"></td>
                            @php
                                $disponibles++;
                            @endphp
                        @else
                            <td class="disponible"></td>
                            <td></td>
                            @php
                                $ocupados++;
                            @endphp
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="centreado">Sin registros</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="2" class="derecha bold">TOTAL</td>
                    <td class="centreado bold">{{ $disponibles }}</td>
                    <td class="centreado bold">{{ $ocupados }}</td>
                </tr>
            </tbody>
        </table>
        @php
            $contador++;
        @endphp
        @if ($contador < count($urbanizacions))
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
