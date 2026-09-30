<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CierreCajaAuditoria extends Model
{
    protected $table = 'cierres_caja_auditorias';
    protected $fillable = ['cierre_caja_id', 'user_id', 'modelo', 'modelo_id', 'accion', 'valores_anteriores', 'valores_nuevos', 'registrado_en'];
    protected $casts = ['valores_anteriores' => 'array', 'valores_nuevos' => 'array', 'registrado_en' => 'datetime'];
}
