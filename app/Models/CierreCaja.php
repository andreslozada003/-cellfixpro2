<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CierreCaja extends Model
{
    protected $table = 'cierres_caja';

    protected $fillable = ['tipo', 'fecha_desde', 'fecha_hasta', 'saldo_inicial', 'dinero_contado', 'saldo_esperado', 'diferencia', 'resumen', 'observaciones', 'user_id', 'cerrado_en'];

    protected $casts = [
        'fecha_desde' => 'date', 'fecha_hasta' => 'date', 'cerrado_en' => 'datetime',
        'saldo_inicial' => 'decimal:2', 'dinero_contado' => 'decimal:2', 'saldo_esperado' => 'decimal:2', 'diferencia' => 'decimal:2', 'resumen' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
