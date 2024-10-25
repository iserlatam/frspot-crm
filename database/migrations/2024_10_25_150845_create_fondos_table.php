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
        Schema::create('fondos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_solicitud_f');
            $table->foreignId('id_propietario');
            $table->string('tipo_solicitud', 25);
            $table->string('estado', 20);
            $table->string('sistema_pago', 25)->nullable();
            $table->dateTime('fecha_creado')->nullable();
            $table->decimal('deposito', 10, 2)->nullable();
            $table->string('divisa', 10)->nullable();
            $table->string('motivo_rechazo', 250)->nullable();
            $table->string('comprobante', 250)->nullable();
            $table->string('radicado', 50)->nullable();
            $table->string('billetera', 250)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fondos');
    }
};
