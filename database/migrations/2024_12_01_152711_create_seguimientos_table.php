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
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->text('descripcion')->nullable();
            $table->string('estado', 20)->nullable();
            $table->string('fase', 50)->nullable();
            $table->string('etiqueta', 50)->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('asesor_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};
