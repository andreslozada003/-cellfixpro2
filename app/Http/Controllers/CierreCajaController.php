<?php

namespace App\Http\Controllers;

use App\Models\CierreCaja;
use App\Services\CierresCajaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CierreCajaController extends Controller
{
    public function __construct(private CierresCajaService $cierres) {}

    public function index(Request $request)
    {
        abort_unless($request->user()->can('ver reportes'), 403);
        $fecha = $request->filled('fecha') ? Carbon::parse($request->fecha) : now();
        $saldoInicial = $this->cierres->saldoInicialPara($fecha);
        $resumen = $this->cierres->resumen($fecha, $fecha, $saldoInicial);
        return view('cierres-caja.index', [
            'fecha' => $fecha, 'resumen' => $resumen, 'saldoInicial' => $saldoInicial,
            'cierreDiario' => CierreCaja::where('tipo', 'diario')->whereDate('fecha_desde', $fecha)->first(),
            'cierreMensual' => CierreCaja::where('tipo', 'mensual')->whereDate('fecha_desde', $fecha->copy()->startOfMonth())->first(),
            'puedeCerrarMensual' => now()->day === 30 && $fecha->isSameMonth(now()),
            'historial' => CierreCaja::with('usuario')->latest('fecha_hasta')->take(12)->get(),
        ]);
    }

    public function cerrarDiario(Request $request)
    {
        abort_unless($request->user()->can('ver reportes'), 403);
        $data = $request->validate(['fecha' => 'required|date|before_or_equal:today', 'saldo_inicial' => 'required|numeric|min:0', 'dinero_contado' => 'required|numeric|min:0', 'observaciones' => 'nullable|string|max:2000']);
        $fecha = Carbon::parse($data['fecha']);
        if (CierreCaja::where('tipo', 'diario')->whereDate('fecha_desde', $fecha)->exists()) throw ValidationException::withMessages(['fecha' => 'Este día ya fue cerrado.']);
        $resumen = $this->cierres->resumen($fecha, $fecha, (float) $data['saldo_inicial']);
        $esperado = $resumen['caja']['saldo_esperado'];
        DB::transaction(fn () => CierreCaja::create(['tipo' => 'diario', 'fecha_desde' => $fecha->toDateString(), 'fecha_hasta' => $fecha->toDateString(), 'saldo_inicial' => $data['saldo_inicial'], 'dinero_contado' => $data['dinero_contado'], 'saldo_esperado' => $esperado, 'diferencia' => round($data['dinero_contado'] - $esperado, 2), 'resumen' => $resumen, 'observaciones' => $data['observaciones'] ?? null, 'user_id' => $request->user()->id, 'cerrado_en' => now()]));
        return redirect()->route('cierres-caja.index', ['fecha' => $fecha->toDateString()])->with('success', 'Cierre diario guardado y movimientos del día bloqueados.');
    }

    public function cerrarMensual(Request $request)
    {
        abort_unless($request->user()->can('ver reportes'), 403);
        if (now()->day !== 30) throw ValidationException::withMessages(['mensual' => 'El cierre mensual solo se habilita el día 30 de cada mes.']);
        $data = $request->validate(['observaciones' => 'nullable|string|max:2000']);
        $desde = now()->startOfMonth(); $hasta = now()->endOfDay();
        if (CierreCaja::where('tipo', 'mensual')->whereDate('fecha_desde', $desde)->exists()) throw ValidationException::withMessages(['mensual' => 'Este mes ya fue cerrado.']);
        $resumen = $this->cierres->resumen($desde, $hasta);
        CierreCaja::create(['tipo' => 'mensual', 'fecha_desde' => $desde, 'fecha_hasta' => $hasta, 'saldo_esperado' => $resumen['caja']['saldo_esperado'], 'resumen' => $resumen, 'observaciones' => $data['observaciones'] ?? null, 'user_id' => $request->user()->id, 'cerrado_en' => now()]);
        return redirect()->route('cierres-caja.index')->with('success', 'Cierre mensual guardado y período bloqueado.');
    }

    public function show(Request $request, CierreCaja $cierreCaja)
    {
        abort_unless($request->user()->can('ver reportes'), 403);
        return view('cierres-caja.show', compact('cierreCaja'));
    }
}
