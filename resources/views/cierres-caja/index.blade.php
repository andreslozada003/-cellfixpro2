@extends('layouts.app')

@section('title', 'Cierre de caja')

@section('page-header')
<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div><h1 class="text-2xl font-bold text-gray-900">Cierre de caja</h1><p class="text-sm text-gray-500">Control diario y consolidado mensual con períodos bloqueados.</p></div>
    <form method="GET" class="flex gap-2"><input type="date" name="fecha" value="{{ $fecha->toDateString() }}" class="rounded-lg border-gray-300 text-sm"><button class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-semibold text-white">Consultar</button></form>
</div>
@endsection

@section('content')
@php $m = fn($v) => '$ '.number_format((float)$v, 0, ',', '.'); @endphp
<div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-xl bg-emerald-600 p-5 text-white shadow"><p class="text-sm opacity-80">Ventas</p><p class="mt-2 text-2xl font-bold">{{ $m($resumen['ventas']['total']) }}</p><p class="mt-1 text-xs">{{ $resumen['ventas']['cantidad'] }} ventas</p></div>
    <div class="rounded-xl bg-blue-600 p-5 text-white shadow"><p class="text-sm opacity-80">Reparaciones</p><p class="mt-2 text-2xl font-bold">{{ $m($resumen['reparaciones']['facturado']) }}</p><p class="mt-1 text-xs">{{ $resumen['reparaciones']['entregadas'] }} entregadas</p></div>
    <div class="rounded-xl bg-violet-600 p-5 text-white shadow"><p class="text-sm opacity-80">Créditos cobrados</p><p class="mt-2 text-2xl font-bold">{{ $m($resumen['creditos']['recaudado']) }}</p><p class="mt-1 text-xs">Cartera: {{ $m($resumen['creditos']['cartera_pendiente']) }}</p></div>
    <div class="rounded-xl bg-amber-500 p-5 text-white shadow"><p class="text-sm opacity-80">Utilidad estimada</p><p class="mt-2 text-2xl font-bold">{{ $m($resumen['mensual']['utilidad_estimada']) }}</p><p class="mt-1 text-xs">Gastos: {{ $m($resumen['mensual']['gastos']) }}</p></div>
</div>

<div class="grid gap-6 xl:grid-cols-3">
 <div class="space-y-6 xl:col-span-2">
  <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"><h2 class="font-bold text-gray-900">Resumen del día — {{ $fecha->translatedFormat('d \d\e F Y') }}</h2>
   <div class="mt-4 grid gap-5 md:grid-cols-3">
    <div><h3 class="mb-2 text-sm font-semibold text-gray-500">VENTAS</h3><dl class="space-y-1 text-sm"><div class="flex justify-between"><dt>Celulares</dt><dd>{{ $m($resumen['ventas']['celulares']) }}</dd></div><div class="flex justify-between"><dt>Accesorios</dt><dd>{{ $m($resumen['ventas']['accesorios']) }}</dd></div><div class="flex justify-between"><dt>Otros</dt><dd>{{ $m($resumen['ventas']['otros']) }}</dd></div><div class="flex justify-between"><dt>Descuentos</dt><dd>{{ $m($resumen['ventas']['descuentos']) }}</dd></div></dl></div>
    <div><h3 class="mb-2 text-sm font-semibold text-gray-500">REPARACIONES</h3><dl class="space-y-1 text-sm"><div class="flex justify-between"><dt>Recibidas</dt><dd>{{ $resumen['reparaciones']['recibidas'] }}</dd></div><div class="flex justify-between"><dt>Entregadas</dt><dd>{{ $resumen['reparaciones']['entregadas'] }}</dd></div><div class="flex justify-between"><dt>Pendientes</dt><dd>{{ $resumen['reparaciones']['pendientes'] }}</dd></div><div class="flex justify-between"><dt>Anticipos</dt><dd>{{ $m($resumen['reparaciones']['anticipos']) }}</dd></div></dl></div>
    <div><h3 class="mb-2 text-sm font-semibold text-gray-500">CAJA</h3><dl class="space-y-1 text-sm"><div class="flex justify-between"><dt>Saldo inicial</dt><dd>{{ $m($saldoInicial) }}</dd></div><div class="flex justify-between"><dt>Ingresos</dt><dd>{{ $m($resumen['caja']['ingresos']) }}</dd></div><div class="flex justify-between"><dt>Egresos</dt><dd>{{ $m($resumen['caja']['egresos']) }}</dd></div><div class="flex justify-between font-bold"><dt>Esperado</dt><dd>{{ $m($resumen['caja']['saldo_esperado']) }}</dd></div></dl></div>
   </div>
  </div>
  <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"><h2 class="font-bold">Indicadores mensuales</h2><div class="mt-4 grid gap-3 sm:grid-cols-2 text-sm"><div class="rounded-lg bg-gray-50 p-3">Costo de mercancía <strong class="float-right">{{ $m($resumen['mensual']['costo_mercancia']) }}</strong></div><div class="rounded-lg bg-gray-50 p-3">Cartera vencida <strong class="float-right">{{ $m($resumen['creditos']['cartera_vencida']) }}</strong></div><div class="rounded-lg bg-gray-50 p-3">Inventario actual <strong class="float-right">{{ $m($resumen['mensual']['inventario_final']) }}</strong></div><div class="rounded-lg bg-gray-50 p-3">Créditos nuevos <strong class="float-right">{{ $m($resumen['creditos']['nuevos']) }}</strong></div></div></div>
 </div>
 <div class="space-y-6">
  <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"><h2 class="font-bold">Cerrar día</h2>
   @if($cierreDiario)<p class="mt-3 rounded-lg bg-emerald-50 p-3 text-sm text-emerald-800">Cerrado por {{ $cierreDiario->usuario->name ?? 'usuario' }}. Diferencia: {{ $m($cierreDiario->diferencia) }}</p><a href="{{ route('cierres-caja.show', $cierreDiario) }}" class="mt-3 inline-block text-sm font-semibold text-primary-600">Ver comprobante →</a>
   @else <form method="POST" action="{{ route('cierres-caja.diario') }}" class="mt-3 space-y-3">@csrf<input type="hidden" name="fecha" value="{{ $fecha->toDateString() }}"><label class="block text-sm">Saldo inicial<input name="saldo_inicial" type="number" min="0" step="0.01" value="{{ $saldoInicial }}" class="mt-1 w-full rounded-lg border-gray-300"></label><label class="block text-sm">Dinero contado físicamente<input name="dinero_contado" type="number" min="0" step="0.01" required class="mt-1 w-full rounded-lg border-gray-300"></label><label class="block text-sm">Observaciones<textarea name="observaciones" class="mt-1 w-full rounded-lg border-gray-300"></textarea></label><button data-confirm="Al cerrar se bloquearán los movimientos de este día. ¿Continuar?" class="w-full rounded-lg bg-primary-600 px-4 py-2 font-semibold text-white">Cerrar día</button></form>@endif
  </div>
  <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"><h2 class="font-bold">Cierre mensual</h2>@if($cierreMensual)<p class="mt-3 text-sm text-emerald-700">Mes cerrado. <a class="font-semibold underline" href="{{ route('cierres-caja.show', $cierreMensual) }}">Ver resumen</a></p>@elseif($puedeCerrarMensual)<form method="POST" action="{{ route('cierres-caja.mensual') }}" class="mt-3">@csrf<button data-confirm="El cierre mensual bloqueará todo el mes. ¿Continuar?" class="w-full rounded-lg bg-gray-900 px-4 py-2 font-semibold text-white">Cerrar {{ now()->translatedFormat('F') }}</button></form>@else<p class="mt-3 rounded-lg bg-amber-50 p-3 text-sm text-amber-800">Se habilita automáticamente el día 30 de cada mes.</p>@endif</div>
 </div>
</div>

<div class="mt-6 rounded-xl border border-gray-200 bg-white p-5 shadow-sm"><h2 class="font-bold">Historial de cierres</h2><div class="mt-3 overflow-x-auto"><table class="w-full text-left text-sm"><thead class="border-b text-gray-500"><tr><th class="p-2">Tipo</th><th class="p-2">Período</th><th class="p-2">Responsable</th><th class="p-2">Cerrado</th><th></th></tr></thead><tbody>@forelse($historial as $c)<tr class="border-b"><td class="p-2 capitalize">{{ $c->tipo }}</td><td class="p-2">{{ $c->fecha_desde->format('d/m/Y') }} — {{ $c->fecha_hasta->format('d/m/Y') }}</td><td class="p-2">{{ $c->usuario->name }}</td><td class="p-2">{{ $c->cerrado_en->format('d/m/Y H:i') }}</td><td><a class="text-primary-600" href="{{ route('cierres-caja.show', $c) }}">Ver</a></td></tr>@empty<tr><td class="p-3 text-gray-500" colspan="5">Aún no hay cierres registrados.</td></tr>@endforelse</tbody></table></div></div>
@endsection
