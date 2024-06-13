<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Salida</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 1.5cm;
            margin-bottom: 1.5cm;
            margin-left: 2cm;
            margin-right: 1.5cm;
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
            font-size: 8pt;
        }

        table tbody tr td {
            font-size: 7.6pt;
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

        .bold {
            font-weight: bold;
        }

        .img_celda img {
            width: 45px;
        }

        .texto_info {
            font-size: 7.7pt;
        }

        .firma {
            margin: auto;
            margin-top: 80px;
            width: 100%;
            border-collapse: separate;
            border-spacing: 80px 0px;
        }

        .firma tbody td {
            text-align: center;
            border-top: dotted 1px black;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    <div class="encabezado">
        <div class="logo">
            <img src="{{ $configuracion->first()->url_logo }}">
        </div>
        <h2 class="titulo">
            {{ $configuracion->first()->razon_social }}
        </h2>
        <h4 class="texto">SALIDA A ALMACÉN</h4>
    </div>

    <table>
        <tbody>
            <tr>
                <td class="bold" width="16%">Unidad Solicitante:</td>
                <td>{{ $salida->unidad_solicitante }}</td>
                <td class="bold" width="14%">Tipo de Salida:</td>
                <td>{{ $salida->tipo_salida->nombre }}</td>
            </tr>
            <tr>
                <td class="bold">Fecha de Salida:</td>
                <td colspan="3">{{ $salida->fecha_salida_t }}</td>
            </tr>
        </tbody>
    </table>

    <table border="1">
        <thead>
            <tr>
                <th width="3%">N°</th>
                <th>PRODUCTOS</th>
                <th width="15%">CANTIDAD</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
                $total = 0;
            @endphp
            @foreach ($salida->salida_detalles as $item)
                <tr>
                    <td>{{ $cont++ }}</td>
                    <td>{{ $item->producto->nombre }}</td>
                    <td class="centreado">{{ $item->cantidad }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="texto_info"><strong>Descripción: </strong> {{ $salida->descripcion }}</p>
    <table class="firma">
        <tbody>
            <tr>
                <td>ALMACÉN</td>
                <td>SOLICITANTE</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
