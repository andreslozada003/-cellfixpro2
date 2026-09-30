<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cierres_caja', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 10); // diario | mensual
            $table->date('fecha_desde');
            $table->date('fecha_hasta');
            $table->decimal('saldo_inicial', 14, 2)->default(0);
            $table->decimal('dinero_contado', 14, 2)->nullable();
            $table->decimal('saldo_esperado', 14, 2)->default(0);
            $table->decimal('diferencia', 14, 2)->default(0);
            $table->json('resumen');
            $table->text('observaciones')->nullable();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->timestamp('cerrado_en');
            $table->timestamps();

            $table->unique(['tipo', 'fecha_desde', 'fecha_hasta']);
            $table->index(['fecha_desde', 'fecha_hasta']);
        });

        Schema::create('cierres_caja_auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cierre_caja_id')->nullable()->constrained('cierres_caja')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('modelo');
            $table->unsignedBigInteger('modelo_id')->nullable();
            $table->string('accion', 20);
            $table->json('valores_anteriores')->nullable();
            $table->json('valores_nuevos')->nullable();
            $table->timestamp('registrado_en');
            $table->timestamps();
            $table->index(['modelo', 'modelo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cierres_caja_auditorias');
        Schema::dropIfExists('cierres_caja');
    }
};
