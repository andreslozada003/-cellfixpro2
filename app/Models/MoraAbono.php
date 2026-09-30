<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Services\CierreBloqueoService;

class MoraAbono extends Model
{
    use HasFactory;

    protected $table = 'mora_abonos';

    protected $fillable = [
        'cliente_id',
        'user_id',
        'tipo',
        'monto',
        'metodo_pago',
        'origen',
        'fecha_pago',
        'notas',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(fn ($abono) => CierreBloqueoService::verificar($abono));
        static::deleting(fn ($abono) => CierreBloqueoService::verificar($abono, 'eliminacion'));
    }

    public function moraAbonable(): MorphTo
    {
        return $this->morphTo();
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
