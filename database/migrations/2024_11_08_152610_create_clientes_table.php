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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 150)->nullable();
            $table->string('identificacion', 50)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ["f","m"])->nullable();
            $table->string('pais', 50)->nullable();
            $table->string('ciudad', 50)->nullable();
            $table->string('direccion', 250)->nullable();
            $table->string('cod_postal', 50)->nullable();
            $table->string('celular', 25)->nullable();
            $table->string('telefono', 25)->nullable();
            $table->boolean('estado')->nullable();
            $table->string('promocion', 1)->nullable();
            $table->string('estado_cliente', 50)->nullable();
            $table->string('fase_cliente', 50)->nullable();
            $table->string('origenes', 60)->nullable();
            $table->string('infoeeuu')->nullable();
            $table->string('caso', 20)->nullable();
            $table->string('tipo_doc_subm', 50)->nullable();
            $table->string('activo_subm', 250)->nullable();
            $table->string('metodo_pago', 25)->nullable();
            $table->string('doc_soporte', 50)->nullable();
            $table->text('archivo_soporte')->nullable();
            $table->text('comprobante_pag')->nullable();
            $table->text('billetera')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
