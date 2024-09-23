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
            background: #1867C0;
            color: white;
        }

        .txt_rojo {}

        .img_celda img {
            width: 45px;
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
        <h4 class="texto">LOTES DE TERRENOS</h4>
        <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
    </div>
    @foreach ($urbanizacions as $urbanizacion)
        <h4>{{ $urbanizacion->nombre }}</h4>
        @php
            $manzanos = App\Models\Manzano::where('urbanizacion_id', $urbanizacion->id)->get();
            if ($manzano_id != 'todos') {
                $manzanos = App\Models\Manzano::where('id', $manzano_id)->get();
            }
        @endphp
        @forelse ($manzanos as $manzano)
            <table border="1">
                <thead>
                    <tr>
                        <th colspan="9">{{ $manzano->nombre }}</th>
                    </tr>
                    <tr>
                        <th width="3%">N°</th>
                        <th>NOMBRE</th>
                        <th>UBICACIÓN</th>
                        <th>ESQUINA</th>
                        <th>ESQUINA ÁREA VERDE</th>
                        <th>ESQUINA ÁREA DE EQUIPAMIENTO</th>
                        <th>AVENIDA ESTRUCTURANTE</th>
                        <th>AVENIDA UNIÓNDE DOS CIUDADES</th>
                        <th>ESTADO</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $lotes = App\Models\Lote::where('manzano_id', $manzano->id)->get();
                        if ($ocupado != 'todos') {
                            $lotes = App\Models\Lote::where('manzano_id', $manzano->id)
                                ->where('vendido', $ocupado)
                                ->get();
                        }
                        $cont = 1;
                    @endphp
                    @forelse ($lotes as $lote)
                        <tr>
                            <td>{{ $cont++ }}</td>
                            <td>{{ $lote->nombre }}</td>
                            <td>{{ $lote->ubicacion }}</td>
                            <td>{{ $lote->esquina }}</td>
                            <td>{{ $lote->esquina_area }}</td>
                            <td>{{ $lote->esquina_equipamiento }}</td>
                            <td>{{ $lote->avenida_estr }}</td>
                            <td>{{ $lote->avenida_union }}</td>
                            <td>{{ $lote->vendido == 1 ? 'VENDIDO' : 'DISPONIBLE' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="centreado">Sin registros</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @empty
            <h5 class="centreado">Sin registros</h5>
        @endforelse
    @endforeach
</body>

</html>
