<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Reparacion #{{ $reparacion->orden }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #1f2937;
            line-height: 1.45;
        }
        .container { padding: 22px; }
        .header {
            border-bottom: 3px solid #1f2937;
            padding-bottom: 14px;
            margin-bottom: 18px;
            text-align: center;
        }
        .header h1 { margin: 0 0 4px; font-size: 24px; }
        .muted { color: #6b7280; }
        .order-box {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 14px;
            margin-bottom: 16px;
        }
        .order-box h2 { margin: 0 0 10px; font-size: 18px; }
        .section {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-bottom: 14px;
            overflow: hidden;
        }
        .section h3 {
            margin: 0;
            padding: 8px 12px;
            background: #111827;
            color: #fff;
            font-size: 13px;
            letter-spacing: .02em;
        }
        .section-body { padding: 12px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 5px 8px; vertical-align: top; }
        .label { color: #4b5563; font-weight: bold; }
        .text-box {
            margin-top: 5px;
            padding: 9px;
            border-radius: 5px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            white-space: pre-line;
        }
        .money-table td {
            border-bottom: 1px solid #e5e7eb;
            padding: 8px;
        }
        .money-table tr:last-child td { border-bottom: 0; }
        .right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1e40af;
            font-weight: bold;
        }
        .terms {
            padding: 12px;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            background: #fffbeb;
            color: #78350f;
            margin-top: 8px;
        }
        .terms h4 { margin: 0 0 8px; color: #92400e; }
        .terms ol { margin: 0 0 0 18px; padding: 0; }
        .signatures {
            display: table;
            width: 100%;
            margin-top: 38px;
        }
        .signature {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 18px;
        }
        .line {
            border-top: 1px solid #111827;
            padding-top: 6px;
        }
        .footer {
            border-top: 1px solid #d1d5db;
            margin-top: 22px;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>
<body>
@php
    $cliente = $reparacion->cliente;
    $tecnico = $reparacion->tecnico;
    $recibidoPor = $reparacion->usuario;
    $costoEstimado = (float) $reparacion->costo_estimado;
    $costoFinalRegistrado = (float) $reparacion->costo_final;
    $costoFinal = $costoFinalRegistrado > 0 ? $costoFinalRegistrado : $costoEstimado;
    $saldoPendiente = max(0, $costoFinal - (float) $reparacion->adelanto);
@endphp

<div class="container">
    <div class="header">
        <h1>{{ $empresa['nombre'] ?? config('app.name', 'CellFix Pro') }}</h1>
        <div class="muted">{{ $empresa['direccion'] ?: 'Direccion no configurada' }}</div>
        <div class="muted">Tel: {{ $empresa['telefono'] ?: 'Telefono no configurado' }}</div>
    </div>

    <div class="order-box">
        <h2>ORDEN DE REPARACION #{{ $reparacion->orden }}</h2>
        <table>
            <tr>
                <td><span class="label">Fecha de recepcion:</span> {{ $reparacion->fecha_recepcion?->format('d/m/Y H:i') ?? 'Sin registrar' }}</td>
                <td><span class="label">Estado:</span> <span class="badge">{{ $reparacion->estado_nombre }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Entrega estimada:</span> {{ $reparacion->fecha_estimada_entrega?->format('d/m/Y') ?? 'Pendiente' }}</td>
                <td><span class="label">Tecnico:</span> {{ $tecnico?->name ?? 'No asignado' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>INFORMACION DEL CLIENTE</h3>
        <div class="section-body">
            <table>
                <tr>
                    <td><span class="label">Nombre:</span> {{ $cliente?->nombre_completo ?? 'Cliente no registrado' }}</td>
                    <td><span class="label">Telefono:</span> {{ $cliente?->telefono ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><span class="label">Email:</span> {{ $cliente?->email ?? 'N/A' }}</td>
                    <td><span class="label">Direccion:</span> {{ $cliente?->direccion ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <h3>INFORMACION DEL EQUIPO</h3>
        <div class="section-body">
            <table>
                <tr>
                    <td><span class="label">Tipo:</span> {{ $reparacion->dispositivo_tipo ?: 'N/A' }}</td>
                    <td><span class="label">Marca:</span> {{ $reparacion->dispositivo_marca ?: 'N/A' }}</td>
                    <td><span class="label">Modelo:</span> {{ $reparacion->dispositivo_modelo ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <td><span class="label">Color:</span> {{ $reparacion->dispositivo_color ?: 'N/A' }}</td>
                    <td><span class="label">IMEI:</span> {{ $reparacion->dispositivo_imei ?: 'N/A' }}</td>
                    <td><span class="label">Serial:</span> {{ $reparacion->dispositivo_serial ?: 'N/A' }}</td>
                </tr>
            </table>
            @if($reparacion->dispositivo_contrasena)
                <div style="margin-top: 8px;"><span class="label">Contrasena / Patron:</span> {{ $reparacion->dispositivo_contrasena }}</div>
            @endif
            @if($reparacion->accesorios_incluidos)
                <div style="margin-top: 8px;"><span class="label">Accesorios incluidos:</span> {{ $reparacion->accesorios_incluidos }}</div>
            @endif
        </div>
    </div>

    <div class="section">
        <h3>PROBLEMA Y DIAGNOSTICO</h3>
        <div class="section-body">
            <div class="label">Problema reportado</div>
            <div class="text-box">{{ $reparacion->problema_reportado ?: 'Sin descripcion del problema.' }}</div>

            @if($reparacion->condiciones_previas)
                <div class="label" style="margin-top: 10px;">Condiciones previas</div>
                <div class="text-box">{{ $reparacion->condiciones_previas }}</div>
            @endif

            @if($reparacion->diagnostico)
                <div class="label" style="margin-top: 10px;">Diagnostico</div>
                <div class="text-box">{{ $reparacion->diagnostico }}</div>
            @endif

            @if($reparacion->solucion)
                <div class="label" style="margin-top: 10px;">Solucion</div>
                <div class="text-box">{{ $reparacion->solucion }}</div>
            @endif

            @if($reparacion->notas_tecnico)
                <div class="label" style="margin-top: 10px;">Notas del tecnico</div>
                <div class="text-box">{{ $reparacion->notas_tecnico }}</div>
            @endif
        </div>
    </div>

    <div class="section">
        <h3>COSTOS Y COBRO</h3>
        <div class="section-body">
            <table class="money-table">
                <tr>
                    <td><span class="label">Costo estimado</span></td>
                    <td class="right">{{ money($costoEstimado) }}</td>
                </tr>
                <tr>
                    <td><span class="label">Costo final</span></td>
                    <td class="right">
                        {{ money($costoFinal) }}
                        @if($costoFinalRegistrado <= 0 && $costoEstimado > 0)
                            <span class="muted">(estimado)</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><span class="label">Adelanto</span></td>
                    <td class="right">{{ money($reparacion->adelanto) }}</td>
                </tr>
                <tr>
                    <td><span class="label">Saldo pendiente</span></td>
                    <td class="right"><strong>{{ money($saldoPendiente) }}</strong></td>
                </tr>
            </table>
        </div>
    </div>

    @if($reparacion->garantia_dias)
        <div class="section">
            <h3>GARANTIA</h3>
            <div class="section-body">
                Esta reparacion cuenta con una garantia de <strong>{{ $reparacion->garantia_dias }} dias</strong> a partir de la fecha de entrega.
            </div>
        </div>
    @endif

    <div class="terms">
        <h4>TERMINOS Y CONDICIONES</h4>
        <ol>
            <li>El cliente acepta que el dispositivo sera revisado para determinar el diagnostico final.</li>
            <li>Los costos estimados pueden variar segun el diagnostico real.</li>
            <li>No nos hacemos responsables por datos perdidos durante la reparacion. Se recomienda hacer backup.</li>
            <li>Los equipos no reclamados en 30 dias despues de notificacion seran dados de baja.</li>
            <li>La garantia cubre unicamente la reparacion realizada y no danos por mal uso o accidentes.</li>
            <li>El cliente debe presentar esta orden para retirar el dispositivo.</li>
        </ol>
    </div>

    <div class="signatures">
        <div class="signature">
            <div class="line">
                Firma del Cliente<br>
                <small>{{ $cliente?->nombre_completo }}</small>
            </div>
        </div>
        <div class="signature">
            <div class="line">
                Firma del Tecnico / Recepcionista<br>
                <small>{{ $tecnico?->name ?? $recibidoPor?->name ?? auth()->user()->name }}</small>
            </div>
        </div>
    </div>

    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }} | Orden {{ $reparacion->orden }}
    </div>
</div>
</body>
</html>
