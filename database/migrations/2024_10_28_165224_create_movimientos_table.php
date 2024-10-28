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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->string('radicado', 50)->nullable();
            $table->string('tipo_solicitud', 25);
            $table->decimal('monto_ingreso', 12, 2)->nullable();
            $table->string('estado_solicitud', 20)->default('pendiente');
            $table->string('billetera', 250)->nullable();
            $table->string('divisa', 10)->nullable();
            $table->string('sistema_pago', 25)->nullable();
            $table->string('comprobante_file', 250)->nullable();
            $table->string('motivo_rechazo', 250)->nullable()->default('ninguno');
            $table->foreignId('cliente_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
