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
            $table->string('no_radicado');
            $table->enum('tipo_st', ["d","r","bn"]);
            $table->enum('est_st', ["a","b","c"])->default('b');
            $table->decimal('ingreso', 12, 3);
            $table->text('comprobante_file')->nullable();
            $table->string('razon_rechazo', 250)->nullable()->default(null);
            $table->foreignUlid('cuenta_cliente_id');
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
