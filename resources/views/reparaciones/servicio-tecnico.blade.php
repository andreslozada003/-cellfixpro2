@extends('layouts.app')

@section('title', 'Servicio Técnico')

@section('page-header')
<div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Servicio Técnico</h1>
        <p class="text-gray-500">Comprobantes de reparación por orden recibida</p>
    </div>
    <a href="{{ route('reparaciones.create') }}" class="inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-white transition-colors hover:bg-primary-700">
        <i class="fas fa-plus mr-2"></i>Nueva Orden
    </a>
</div>
@endsection

@section('content')
<div class="mb-6 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
    <form action="{{ route('reparaciones.servicio-tecnico') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-[1fr_auto]">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Buscar comprobante</label>
            <input type="text" name="search" value="{{ request('search') }}"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-primary-500 focus:ring-2 focus:ring-primary-500"
                placeholder="Orden, cliente, teléfono, equipo o diagnóstico">
        </div>
        <div class="flex items-end">
            <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-white transition-colors hover:bg-primary-700 md:w-auto">
                <i class="fas fa-search mr-2"></i>Filtrar
            </button>
        </div>
    </form>
</div>

<div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Comprobante</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Cliente</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Equipo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Diagnóstico</th>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</th>
                    <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($reparaciones as $reparacion)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="font-semibold text-gray-900">{{ $reparacion->orden }}</div>
                        <div class="text-xs text-gray-500">{{ $reparacion->estado_nombre }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-gray-900">{{ $reparacion->cliente?->nombre_completo ?? 'Cliente no registrado' }}</div>
                        <div class="text-sm text-gray-500">{{ $reparacion->cliente?->telefono ?? 'Sin teléfono' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-gray-900">{{ $reparacion->dispositivo_tipo ?: 'Sin tipo' }}</div>
                        <div class="text-sm text-gray-500">{{ trim($reparacion->dispositivo_marca . ' ' . $reparacion->dispositivo_modelo) ?: 'Sin marca/modelo' }}</div>
                    </td>
                    <td class="max-w-md px-4 py-3 text-sm text-gray-600">
                        {{ \Illuminate\Support\Str::limit($reparacion->diagnostico ?: $reparacion->problema_reportado ?: 'Sin diagnóstico registrado', 110) }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        {{ $reparacion->fecha_recepcion?->format('d/m/Y') ?? '-' }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('reparaciones.show', $reparacion) }}" class="text-blue-600 hover:text-blue-700" title="Ver orden">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('reparaciones.comprobante-servicio-tecnico', $reparacion) }}" target="_blank" class="text-primary-600 hover:text-primary-700" title="Comprobante de reparación">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-gray-500">
                        <i class="fas fa-file-medical text-4xl text-gray-300"></i>
                        <p class="mt-3">No hay reparaciones para generar comprobante.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-gray-200 px-4 py-3">
        {{ $reparaciones->withQueryString()->links() }}
    </div>
</div>
@endsection
