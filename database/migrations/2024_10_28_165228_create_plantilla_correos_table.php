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
        Schema::create('plantilla_correos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_plantilla', 150)->nullable();
            $table->text('cuerpo_plantilla')->nullable();
            $table->string('asunto_plantilla', 150)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plantilla_correos');
    }
};
