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
            $table->string('identificacion', 50)->nullable();
            $table->string('nombre', 150)->nullable();
            $table->string('direccion', 250)->nullable();
            $table->string('genero', 1)->nullable();
            $table->string('celular', 50)->nullable();
            $table->date('fecha_nacido')->nullable();
            $table->dateTime('fecha_sistema')->nullable();
            $table->integer('estado')->nullable();
            $table->string('promocion', 1)->nullable();
            $table->string('codigo_unico', 20)->nullable();
            $table->string('ciudad', 50)->nullable();
            $table->string('infoeeuu', 2)->nullable();
            $table->string('caso', 20)->nullable();
            $table->string('tipo_doc_subm', 50)->nullable();
            $table->string('activo_subm', 250)->nullable();
            $table->string('metodoPago', 25)->nullable();
            $table->string('cod_postal', 50)->nullable();
            $table->string('pais', 50)->nullable();
            $table->decimal('monto_pag', 10, 2)->nullable();
            $table->string('doc_soporte', 50)->nullable();
            $table->string('archivo_soporte', 250)->nullable();
            $table->string('comprobante_pag', 250)->nullable();
            $table->string('estado_cliente', 50)->nullable();
            $table->string('fase_cliente', 50)->nullable();
            $table->string('origenes', 250)->nullable();
            $table->string('billetera', 250)->nullable();
            $table->foreignId('user_id')->nullable();
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
