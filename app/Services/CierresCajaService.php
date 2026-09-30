<?php

namespace App\Services;

use App\Models\CierreCaja;
use App\Models\Expense;
use App\Models\MoraAbono;
use App\Models\InventarioMovimiento;
use App\Models\Producto;
use App\Models\Reparacion;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CierresCajaService
{
    public function resumen(Carbon $desde, Carbon $hasta, float $saldoInicial = 0): array
    {
        $desde = $desde->copy()->startOfDay();
        $hasta = $hasta->copy()->endOfDay();
        $ventas = Venta::with(['detalles.producto', 'usuario'])->whereBetween('fecha_venta', [$desde, $hasta])->where('estado', '!=', 'cancelada')->get();
        $ventasAnuladas = Venta::whereBetween('fecha_venta', [$desde, $hasta])->where('estado', 'cancelada')->get();
        $reparaciones = Reparacion::with('tecnico')->whereBetween('fecha_recepcion', [$desde, $hasta])->where('estado', '!=', 'cancelado')->get();
        $abonos = MoraAbono::whereBetween('fecha_pago', [$desde, $hasta])->get();
        $gastos = Expense::whereBetween('paid_date', [$desde->toDateString(), $hasta->toDateString()])->where('payment_status', '!=', 'cancelled')->get();
        $comprasInventario = (float) InventarioMovimiento::whereBetween('created_at', [$desde, $hasta])->whereIn('tipo', ['compra', 'entrada'])->get()->sum(fn ($m) => $m->cantidad * (float) $m->costo_unitario);

        $ventasPorTipo = ['celular' => 0.0, 'accesorio' => 0.0, 'otros' => 0.0];
        $costoMercancia = 0.0;
        foreach ($ventas as $venta) foreach ($venta->detalles as $detalle) {
            $tipo = $detalle->producto->tipo ?? 'otros';
            $grupo = $tipo === 'celular' ? 'celular' : ($tipo === 'accesorio' ? 'accesorio' : 'otros');
            $ventasPorTipo[$grupo] += (float) $detalle->subtotal;
            $costoMercancia += (float) ($detalle->producto->precio_compra ?? 0) * $detalle->cantidad;
        }
        $ventasPorPago = $ventas->groupBy(fn ($v) => $v->metodo_pago ?: 'sin_definir')->map(fn ($g) => (float) $g->sum('total'))->all();
        $abonosVentas = $abonos->where('mora_abonable_type', Venta::class);
        $abonosReparaciones = $abonos->where('mora_abonable_type', Reparacion::class);
        $reparacionesEntregadas = Reparacion::whereBetween('fecha_entrega', [$desde, $hasta])->where('estado', 'entregado')->get();
        $carteraVentas = (float) Venta::where('estado', '!=', 'cancelada')->get()->sum(fn ($v) => $v->saldo_pendiente_mora);
        $carteraReparaciones = (float) Reparacion::where('estado', '!=', 'cancelado')->get()->sum(fn ($r) => $r->saldo_pendiente_mora);
        $carteraVencida = (float) Venta::where('estado', '!=', 'cancelada')->whereDate('fecha_compromiso_pago', '<', today())->get()->sum(fn ($v) => $v->saldo_pendiente_mora);

        $ventasEfectivo = (float) ($ventasPorPago['efectivo'] ?? 0);
        $abonosEfectivo = (float) $abonos->filter(fn ($a) => in_array($a->metodo_pago, ['efectivo', 'cash'], true))->sum('monto');
        $gastosEfectivo = (float) $gastos->filter(fn ($g) => in_array($g->payment_method, ['cash', 'efectivo'], true))->sum('paid_amount');
        $ingresos = (float) $ventas->sum('total') + (float) $abonos->sum('monto');
        $egresos = (float) $gastos->sum(fn ($g) => $g->paid_amount > 0 ? $g->paid_amount : $g->amount);
        $saldoEsperado = $saldoInicial + $ventasEfectivo + $abonosEfectivo - $gastosEfectivo;

        return [
            'ventas' => ['total' => (float) $ventas->sum('total'), 'cantidad' => $ventas->count(), 'celulares' => $ventasPorTipo['celular'], 'accesorios' => $ventasPorTipo['accesorio'], 'otros' => $ventasPorTipo['otros'], 'contado' => $ventasEfectivo, 'transferencia' => (float) ($ventasPorPago['transferencia'] ?? 0), 'tarjeta' => (float) ($ventasPorPago['tarjeta'] ?? 0), 'credito' => (float) ($ventasPorPago['credito'] ?? 0), 'descuentos' => (float) $ventas->sum('descuento'), 'anulaciones' => (float) $ventasAnuladas->sum('total'), 'por_metodo' => $ventasPorPago],
            'reparaciones' => ['recibidas' => $reparaciones->count(), 'entregadas' => $reparacionesEntregadas->count(), 'pendientes' => Reparacion::whereNotIn('estado', ['entregado', 'cancelado'])->count(), 'facturado' => (float) $reparacionesEntregadas->sum('costo_final'), 'anticipos' => (float) $abonosReparaciones->sum('monto'), 'saldo_pendiente' => $carteraReparaciones, 'ganancia' => (float) $reparacionesEntregadas->sum('ganancia')],
            'creditos' => ['nuevos' => (float) $ventas->where('metodo_pago', 'credito')->sum('total'), 'cuotas_cobradas' => (float) $abonosVentas->sum('monto'), 'abonos_extraordinarios' => (float) $abonosVentas->where('tipo', 'extraordinario')->sum('monto'), 'recaudado' => (float) $abonosVentas->sum('monto'), 'cartera_pendiente' => $carteraVentas, 'cartera_vencida' => $carteraVencida],
            'caja' => ['saldo_inicial' => $saldoInicial, 'ingresos' => $ingresos, 'egresos' => $egresos, 'retiros' => 0.0, 'saldo_esperado' => $saldoEsperado],
            'mensual' => ['costo_mercancia' => $costoMercancia, 'compras_inventario' => $comprasInventario, 'gastos' => $egresos, 'utilidad_estimada' => (float) $ventas->sum('total') + (float) $reparacionesEntregadas->sum('costo_final') - $costoMercancia - $egresos, 'inventario_final' => (float) Producto::where('activo', true)->sum(DB::raw('stock * precio_compra'))],
            'ventas_por_usuario' => $ventas->groupBy('user_id')->map(fn ($g) => ['nombre' => optional($g->first()->usuario)->name ?? 'Sin usuario', 'total' => (float) $g->sum('total')])->values()->all(),
            'reparaciones_por_tecnico' => $reparacionesEntregadas->groupBy('tecnico_id')->map(fn ($g) => ['nombre' => optional($g->first()->tecnico)->name ?? 'Sin técnico', 'total' => (float) $g->sum('costo_final')])->values()->all(),
        ];
    }

    public function saldoInicialPara(Carbon $fecha): float
    {
        return (float) (CierreCaja::where('tipo', 'diario')->whereDate('fecha_hasta', '<', $fecha->toDateString())->latest('fecha_hasta')->value('dinero_contado') ?? 0);
    }
}
