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
        // ASESORES OK
        Schema::create('asesors', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_asesor', 20)->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });

        // ASIGNACIONES OK
        Schema::create('asignacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('asesor_id');
            $table->boolean('estado_asignacion')->default(true);
            $table->timestamps();
        });

        // CLIENTES OK
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
            $table->string('promocion', 50)->nullable();
            $table->string('estado_cliente', 50)->nullable();
            $table->string('fase_cliente', 50)->nullable();
            $table->string('origenes', 60)->nullable();
            $table->string('infoeeuu')->nullable();
            $table->string('caso', 50)->nullable();
            $table->string('tipo_doc_subm', 50)->nullable();
            $table->string('activo_subm', 250)->nullable();
            $table->string('metodo_pago', 25)->nullable();
            $table->string('doc_soporte', 50)->nullable();
            $table->text('archivo_soporte')->nullable();
            $table->text('comprobante_pag')->nullable();
            $table->foreignId('user_id');
            $table->timestamps();
        });

        // CUENTAS CLIENTES
        Schema::create('cuenta_clientes', function (Blueprint $table) {
            $table->uuid("id");
            $table->string('sistema_pago')->nullable();
            $table->text('billetera')->nullable();
            $table->string('divisa', 15)->nullable();
            $table->boolean('estado_cuenta')->default(true);
            // Save the last movement ID related with this account
            $table->foreignId('ultimo_movimiento')->nullable();
            $table->decimal('monto_total', 15, 3)->default(0)->nullable();
            $table->decimal('sum_dep', 15, 3)->default(0)->nullable();
            $table->string('no_dep')->default('0')->default('0')->nullable();
            $table->decimal('sum_retiros', 15, 3)->default(0)->nullable();
            $table->string('no_retiros')->default('0')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // MOVIMIENTOS OK
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->string('no_radicado');
            $table->enum('tipo_st', ["d","r"]);
            $table->enum('est_st', ["a","b","c"])->default('b');
            $table->decimal('ingreso', 12, 3);
            $table->text('comprobante_file')->nullable();
            $table->string('razon_rechazo', 250)->nullable()->default(null);
            $table->foreignUuid('cuenta_cliente_id')->references('id')->on('cuenta_clientes');
            $table->timestamps();
        });

        // SEGUIMIENTOS
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->text('descripción')->nullable();
            $table->string('estado', 20)->nullable();
            $table->string('fase', 50)->nullable();
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
        Schema::drop('asesors');
        Schema::drop('asignacions');
        Schema::drop('clientes');
        Schema::drop('cuenta_clientes');
        Schema::drop('movimientos');
        Schema::drop('seguimientos');
    }
};
