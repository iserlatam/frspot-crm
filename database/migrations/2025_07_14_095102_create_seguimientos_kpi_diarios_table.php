<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('seguimientos_kpi_diarios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('asesor_id')->index();      // Para búsquedas por asesor
            $table->string('nombre_asesor');                      // Nombre snapshot
            $table->string('rol_asesor')->index();                // Rol: asesor, team ftd, etc.
            $table->string('tipo_asesor')->index();               // Tipo de asesor: FTD, retencion, recovery
            $table->date('fecha_kpi')->index();                   // Fecha del KPI (snapshot diario)
            $table->integer('cantidad_clientes');                 // # de clientes distintos contactados
            $table->integer('cantidad_total');                    // # total de seguimientos
            $table->boolean('cumplio_meta');                      // Si cumplió meta o no (según la oficina)
            $table->integer('faltantes')->nullable();             // Cuántos le faltaron para la meta
            
            // $table->string('oficina')->index();                   // Oficina/departamento
            $table->timestamps();

            // Asegura que solo haya 1 registro por asesor/oficina/día
            $table->unique(['asesor_id', 'fecha_kpi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimientos_kpi_diarios');
    }
};
