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
            $table->string('radicado', 50);
            $table->enum('tipo_solicitud', ["deposito","retiro"]);
            $table->enum('estado_solicitud', ["aprobado","pendiente","rechazado"])->default('pendiente');
            $table->decimal('monto_ingreso', 12, 2);
            $table->string('sistema_pago', 50)->nullable();
            $table->text('billetera')->nullable();
            $table->string('divisa', 15)->nullable();
            $table->text('comprobante_file')->nullable();
            $table->string('motivo_rechazo', 250)->nullable()->default('ninguno');
            $table->foreignId('cliente_id')->constrained();
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
