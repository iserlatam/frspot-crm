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
        Schema::create('cuenta_clientes', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('sistema_pago')->nullable();
            $table->text('billetera')->nullable();
            $table->string('divisa', 15)->nullable();
            $table->boolean('estado_cuenta')->default(true);
            $table->foreignId('ultimo_movimiento_id')->nullable();
            $table->decimal('monto_total', 15, 3)->default(0)->nullable();
            $table->decimal('sum_dep', 15, 3)->default(0)->nullable();
            $table->string('no_dep')->default('0')->default('0')->nullable();
            $table->decimal('sum_retiros', 15, 3)->default(0)->nullable();
            $table->string('no_retiros')->default('0')->nullable();
            $table->foreignId('user_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuenta_clientes');
    }
};
