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
            $table->uuid('id');
            $table->text('billetera')->nullable();
            $table->string('divisa', 15)->nullable();
            $table->decimal('monto_total', 15, 2)->default(0)->nullable();
            $table->dateTime('ultimo_movimiento')->nullable();
            $table->decimal('suma_total_depositos', 15, 2)->default(0)->nullable();
            $table->string('no_depositos')->default('0')->nullable();
            $table->decimal('suma_total_retiros', 15, 2)->default(0)->nullable();
            $table->string('no_retiros')->default('0')->nullable();
            $table->foreignId('cliente_id')
                ->constrained()
                ->cascadeOnDelete();
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
