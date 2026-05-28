<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Reparación {{ $reparacion->orden }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: #fff;
            color: #111;
            font-family: DejaVu Sans, Arial, sans-serif;
        }
        .label {
            width: 100%;
            height: 100%;
            padding: 14px 18px 12px;
            text-align: center;
        }
        .business {
            margin: 0;
            font-size: 30px;
            font-weight: 900;
            letter-spacing: 2px;
            line-height: 1;
            text-transform: uppercase;
        }
        .barcode {
            height: 45px;
            margin: 12px auto 5px;
            overflow: hidden;
            text-align: center;
            white-space: nowrap;
        }
        .bar {
            display: inline-block;
            height: 45px;
            margin: 0 1px 0 0;
            background: #111;
            vertical-align: top;
        }
        .code {
            margin: 0;
            font-size: 50px;
            font-weight: 900;
            letter-spacing: 3px;
            line-height: .95;
        }
        .device {
            margin: 4px 0 0;
            font-size: 29px;
            font-weight: 900;
            letter-spacing: 1px;
            line-height: 1.05;
            text-transform: uppercase;
        }
        .summary {
            margin-top: 9px;
            border-top: 1px solid #111;
            padding-top: 6px;
            font-size: 10px;
            line-height: 1.25;
            text-align: left;
        }
        .summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary td {
            padding: 1px 4px;
            vertical-align: top;
        }
        .summary .key {
            width: 54px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .muted {
            color: #333;
        }
    </style>
</head>
<body>
@php
    $cliente = $reparacion->cliente;
    $empresaNombre = $empresa['nombre'] ?? config('app.name', 'Servicio Tecnico');
    $empresaNombre = \Illuminate\Support\Str::upper($empresaNombre);
    $ordenNumerica = preg_replace('/\D+/', '', $reparacion->orden);
    $codigoGrande = $ordenNumerica ? substr($ordenNumerica, -4) : str_pad((string) $reparacion->id, 4, '0', STR_PAD_LEFT);
    $equipo = trim(collect([
        $reparacion->dispositivo_tipo,
        $reparacion->dispositivo_marca,
        $reparacion->dispositivo_modelo,
    ])->filter()->implode(' '));
    $equipo = $equipo ?: 'Equipo no registrado';
    $diagnostico = $reparacion->diagnostico ?: $reparacion->problema_reportado ?: 'Sin diagnostico';
    $barcodeSeed = str_split($reparacion->orden . $codigoGrande . $reparacion->id);
@endphp

<div class="label">
    <h1 class="business">{{ $empresaNombre }}</h1>

    <div class="barcode" aria-hidden="true">
        @foreach($barcodeSeed as $index => $char)
            @php
                $width = (ord($char) + $index) % 4 + 2;
                $height = 31 + ((ord($char) + $index) % 15);
            @endphp
            <span class="bar" style="width: {{ $width }}px; height: {{ $height }}px;"></span>
        @endforeach
    </div>

    <p class="code">{{ $codigoGrande }}</p>
    <p class="device">{{ \Illuminate\Support\Str::limit($equipo, 22, '') }}</p>

    <div class="summary">
        <table>
            <tr>
                <td class="key">Cliente</td>
                <td>{{ \Illuminate\Support\Str::limit($cliente?->nombre_completo ?: 'Sin cliente', 34) }}</td>
                <td class="key">Tel</td>
                <td>{{ $cliente?->telefono ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td class="key">Diag.</td>
                <td colspan="3" class="muted">{{ \Illuminate\Support\Str::limit($diagnostico, 86) }}</td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
