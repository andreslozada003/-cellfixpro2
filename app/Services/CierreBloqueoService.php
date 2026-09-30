<?php

namespace App\Services;

use App\Models\CierreCaja;
use App\Models\CierreCajaAuditoria;
use App\Models\Expense;
use App\Models\MoraAbono;
use App\Models\Reparacion;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class CierreBloqueoService
{
    public static function verificar(Model $model, string $accion = 'actualizacion'): void
    {
        if (! Schema::hasTable('cierres_caja')) return;
        $campos = match (get_class($model)) {
            Venta::class => ['fecha_venta'], Reparacion::class => ['fecha_recepcion', 'fecha_entrega'],
            Expense::class => ['expense_date', 'paid_date'], MoraAbono::class => ['fecha_pago'], default => [],
        };
        foreach ($campos as $campo) foreach (array_unique(array_filter([$model->getOriginal($campo), $model->getAttribute($campo)])) as $fecha) {
            $dia = Carbon::parse($fecha)->toDateString();
            $cierre = CierreCaja::where(fn ($q) => $q->where('tipo', 'diario')->whereDate('fecha_desde', $dia)->whereDate('fecha_hasta', $dia))
                ->orWhere(fn ($q) => $q->where('tipo', 'mensual')->whereDate('fecha_desde', '<=', $dia)->whereDate('fecha_hasta', '>=', $dia))->first();
            if ($cierre) {
                $usuario = auth()->user();
                if ($usuario?->hasRole('admin')) {
                    CierreCajaAuditoria::create([
                        'cierre_caja_id' => $cierre->id, 'user_id' => $usuario->id, 'modelo' => get_class($model), 'modelo_id' => $model->getKey(), 'accion' => $accion,
                        'valores_anteriores' => $model->getOriginal(), 'valores_nuevos' => $accion === 'eliminacion' ? null : $model->getAttributes(), 'registrado_en' => now(),
                    ]);
                    return;
                }
                throw ValidationException::withMessages(['cierre' => 'Este movimiento pertenece a un período cerrado. Solo una autorización auditada puede modificarlo.']);
            }
        }
    }
}
