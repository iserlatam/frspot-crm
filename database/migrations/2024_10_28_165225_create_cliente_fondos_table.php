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
        Schema::create('cliente_fondos', function (Blueprint $table) {
            $table->id();
            $table->decimal('monto_total', 12, 2)->nullable();
            $table->decimal('total_depositos', 12, 2)->nullable();
            $table->decimal('total_retiros', 12, 2)->nullable();
            $table->foreignId('cliente_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_fondos');
    }
};
