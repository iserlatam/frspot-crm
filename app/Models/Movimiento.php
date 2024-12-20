<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Movimiento extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no_radicado',
        'tipo_st',
        'est_st',
        'ingreso',
        'comprobante_file',
        'razon_rechazo',
        'cuenta_cliente_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'ingreso' => 'decimal:3',
    ];

    public static function generateRadicado()
    {
        $numbers = random_int(0, 999999);
        return "ra{$numbers}";
    }

    public function chargeAccount(array $body)
    {
        $case = $body['case'];
        $movimiento = $body['movimiento'];

        $currentCuenta = CuentaCliente::findOrFail($movimiento->cuenta_cliente_id);

        // Movimiento aprobado
        if ($case === 'a') {

            // Deposito
            if ($movimiento->tipo_st === 'd') {
                try {
                    DB::beginTransaction();

                    // Transacciones correspondientes al deposito
                    $currentCuenta->monto_total += $movimiento->ingreso;
                    $currentCuenta->no_dep += 1;
                    $currentCuenta->sum_dep += $movimiento->ingreso;
                    $currentCuenta->ultimo_movimiento_id = $movimiento->id;
                    $currentCuenta->update();

                    DB::commit();
                } catch (\Throwable $e) {
                    DB::rollBack();
                    throw new \Exception('Error al procesar el movimiento: ' . $e->getMessage(), 0, $e);
                } finally {
                    $this->est_st = 'a';
                    $this->update();
                }
            }
            // Retiro
            else if ($movimiento->tipo_st === 'r') {
                // Escribir logica de retiro aqui
                try {
                    DB::beginTransaction();

                    $currentCuenta->monto_total -= $movimiento->ingreso;
                    $currentCuenta->no_retiros += 1;
                    $currentCuenta->sum_retiros += $movimiento->ingreso;
                    $currentCuenta->ultimo_movimiento_id = $movimiento->id;
                    $currentCuenta->update();

                    DB::commit();
                } catch (\Throwable $e) {
                    DB::rollback();
                    throw new \Exception('Error al procesar el movimiento: ' . $e->getMessage(), 0, $e);
                } finally {
                    $this->est_st = 'a';
                    $this->update();
                }
            }
        }
        // Movimiento rechazado
        else if ($case === 'c') {
            $this->est_st = 'a';
            $this->update();
        }
    }

    public function cuentaCliente(): BelongsTo
    {
        return $this->belongsTo(CuentaCliente::class);
    }
}
