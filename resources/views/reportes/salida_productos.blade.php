<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>SalidaProductos</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 1.5cm;
            margin-bottom: 0.5cm;
            margin-left: 0.5cm;
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

        .txt_rojo {}

        .img_celda img {
            width: 45px;
        }

        .title_lugar {
            font-size: 9pt;
            margin-bottom: 0px;
        }

        .nueva_pagina {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @if ($lugar == 'ALMACÉN')
        <div class="encabezado">
            <div class="logo">
                <img src="{{ $configuracion->first()->logo_b64 }}">
            </div>
            <h2 class="titulo">
                {{ $configuracion->first()->razon_social }}
            </h2>
            <h4 class="texto">SALIDA DE PRODUCTOS</h4>
            <h4 class="texto">ALMACÉN</h4>
            <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
        </div>
        <table border="1" style="margin-top:0px">
            <thead>
                <tr class="bg-principal">
                    <th width="4%">#</th>
                    <th>PRODUCTO</th>
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
            <tbody>
                @php
                    $cont = 1;

                    $salida_productos = App\Models\SalidaProducto::select('salida_productos.*')
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

                @endphp
                @foreach ($salida_productos as $salida_producto)
                    <tr>
                        <td>{{ $cont++ }}</td>
                        <td>{{ $salida_producto->producto->nombre }}</td>
                        <td>{{ $salida_producto->producto->categoria->nombre }}</td>
                        <td>{{ $salida_producto->producto->marca->nombre }}</td>
                        <td>{{ $salida_producto->producto->unidad_medida->nombre }}</td>
                        <td class="centreado">{{ $salida_producto->cantidad }}</td>
                        <td>{{ $salida_producto->tipo_salida->nombre }}</td>
                        <td>{{ $salida_producto->descripcion }}</td>
                        <td>{{ $salida_producto->fecha_salida_t }}</td>
                        <td>{{ $salida_producto->fecha_registro_t }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        @php
            $contador_s = 0;
        @endphp
        @foreach ($sucursals as $sucursal)
            <div class="encabezado">
                <div class="logo">
                    <img src="{{ $configuracion->first()->logo_b64 }}">
                </div>
                <h2 class="titulo">
                    {{ $configuracion->first()->razon_social }}
                </h2>
                <h4 class="texto">SALIDA DE PRODUCTOS</h4>
                <h4 class="texto">{{ $sucursal->nombre }}</h4>
                <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
            </div>
            <table border="1" style="margin-top:0px">
                <thead>
                    <tr class="bg-principal">
                        <th width="4%">#</th>
                        <th>PRODUCTO</th>
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
                <tbody>
                    @php
                        $cont = 1;

                        $salida_productos = App\Models\SalidaProducto::select('salida_productos.*')
                            ->join('productos', 'productos.id', '=', 'salida_productos.producto_id')
                            ->where('lugar', 'SUCURSAL')
                            ->where('sucursal_id', $sucursal->id);
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

                    @endphp
                    @foreach ($salida_productos as $salida_producto)
                        <tr>
                            <td>{{ $cont++ }}</td>
                            <td>{{ $salida_producto->producto->nombre }}</td>
                            <td>{{ $salida_producto->producto->categoria->nombre }}</td>
                            <td>{{ $salida_producto->producto->marca->nombre }}</td>
                            <td>{{ $salida_producto->producto->unidad_medida->nombre }}</td>
                            <td class="centreado">{{ $salida_producto->cantidad }}</td>
                            <td>{{ $salida_producto->tipo_salida->nombre }}</td>
                            <td>{{ $salida_producto->descripcion }}</td>
                            <td>{{ $salida_producto->fecha_salida_t }}</td>
                            <td>{{ $salida_producto->fecha_registro_t }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @php
                $contador_s++;
            @endphp

            @if ($contador_s < count($sucursals))
                <div class="nueva_pagina"></div>
            @endif
        @endforeach
    @endif
</body>

</html>
