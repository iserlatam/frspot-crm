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
            $table->string('genero', 15)->nullable();
            $table->string('pais', 70)->nullable();
            $table->string('ciudad', 50)->nullable();
            $table->string('direccion', 250)->nullable();
            $table->string('cod_postal', 50)->nullable();
            $table->string('celular', 25)->nullable();
            $table->string('telefono', 25)->nullable();
            $table->boolean('is_activo')->default(true);

            $table->string('estado_cliente', 50)->nullable();
            $table->string('fase_cliente', 50)->nullable();

            // Amazon, Oro, Como entro
            $table->string('origenes', 60)->nullable();

            // Cuestionario de estados unidos
            $table->string('infoeeuu')->nullable();
            $table->string('caso', 50)->nullable();

            $table->string('tipo_doc_id', 50)->nullable();
            $table->text('file_id')->nullable();

            $table->boolean('est_docs')->nullable()->default(false);

            $table->string('tipo_doc_soporte', 50)->nullable();
            $table->text('file_soporte')->nullable();

            // Primer deposito
            $table->text('comprobante_pag')->nullable();
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
