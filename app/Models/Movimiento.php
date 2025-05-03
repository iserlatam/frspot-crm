<?php

namespace App\Models;

use App\Helpers\Helpers;
use App\Helpers\NotificationHelpers;
use Filament\Notifications\Notification;
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
            switch ($movimiento->tipo_st) {
                // Deposito
                case 'd':
                    try {
                        DB::beginTransaction();

                        // Transacciones correspondientes al deposito
                        $currentCuenta->monto_total += $movimiento->ingreso;
                        $currentCuenta->no_dep += 1;
                        $currentCuenta->sum_dep += $movimiento->ingreso;
                        $currentCuenta->ultimo_movimiento_id = $movimiento->id;
                        $currentCuenta->update();

                        $this->est_st = 'a';
                        $this->update();

                        DB::commit();
                        NotificationHelpers::notifyByTipoMovimiento($movimiento->tipo_st);
                        return;
                    } catch (\Throwable $e) {
                        DB::rollBack();
                        return Helpers::sendErrorNotification($e->getMessage());
                    }
                    // GANANCIAS
                case 'g':
                    try {
                        DB::beginTransaction();

                        // Transacciones correspondientes al deposito
                        $currentCuenta->monto_total += $movimiento->ingreso;
                        $currentCuenta->no_dep += 1;
                        $currentCuenta->sum_dep += $movimiento->ingreso;
                        $currentCuenta->ultimo_movimiento_id = $movimiento->id;
                        $currentCuenta->update();

                        $this->est_st = 'a';
                        $this->update();

                        DB::commit();
                        NotificationHelpers::notifyByTipoMovimiento($movimiento->tipo_st);
                        return;
                    } catch (\Throwable $e) {
                        DB::rollBack();
                        return Helpers::sendErrorNotification($e->getMessage());
                    }
                    // Bono
                case 'b':
                    try {
                        DB::beginTransaction();

                        // Transacciones correspondientes al deposito
                        $currentCuenta->monto_total += $movimiento->ingreso;
                        $currentCuenta->ultimo_movimiento_id = $movimiento->id;
                        $currentCuenta->update();

                        $this->est_st = 'a';
                        $this->update();

                        DB::commit();
                        NotificationHelpers::notifyByTipoMovimiento($movimiento->tipo_st);
                        return;
                    } catch (\Throwable $e) {
                        DB::rollBack();
                        return Helpers::sendErrorNotification($e->getMessage());
                    }
                    // Retiro
                case 'r':
                    try {
                        DB::beginTransaction();

                        if ($currentCuenta->monto_total < $movimiento->ingreso) {
                            DB::commit();
                            Helpers::sendErrorNotification('No se puede realizar el retiro, saldo insuficiente');
                            return;
                        }

                        $currentCuenta->monto_total -= $movimiento->ingreso;
                        $currentCuenta->no_retiros += 1;
                        $currentCuenta->sum_retiros += $movimiento->ingreso;
                        $currentCuenta->ultimo_movimiento_id = $movimiento->id;
                        $currentCuenta->update();

                        $this->est_st = 'a';
                        $this->update();

                        DB::commit();
                        NotificationHelpers::notifyByTipoMovimiento($movimiento->tipo_st);
                        return;
                    } catch (\Throwable $e) {
                        DB::rollback();
                        return Helpers::sendErrorNotification($e->getMessage());
                    }
                case 'p':
                    try {
                        DB::beginTransaction();

                        // if ($currentCuenta->monto_total < $movimiento->ingreso) {
                        //     return Helpers::sendErrorNotification('No se puede realizar el retiro, saldo insuficiente');
                        // }

                        $currentCuenta->monto_total -= $movimiento->ingreso;
                        // $currentCuenta->no_retiros += 1;
                        $currentCuenta->sum_retiros += $movimiento->ingreso;
                        $currentCuenta->ultimo_movimiento_id = $movimiento->id;
                        $currentCuenta->update();

                        $this->est_st = 'a';
                        $this->update();

                        DB::commit();
                        NotificationHelpers::notifyByTipoMovimiento($movimiento->tipo_st);
                        return;
                    } catch (\Throwable $e) {
                        DB::rollback();
                        return Helpers::sendErrorNotification($e->getMessage());
                    }
            }
        }
        // Movimiento rechazado
        else if ($case === 'c') {
            // Obtener ambas fechas de creacion y actualizacion
            $creationDate = Carbon::parse($this->created_at);
            $updatedDate = Carbon::parse($this->updated_at);

            // Comparar si la fecha de actualizacion es mayor a la fecha de creacion
            if ($updatedDate->greaterThan($creationDate)) {
                // Validar el estado y de acuerdo a eso, restar o aumentar
                if ($this->est_st === 'a') {
                    DB::beginTransaction();
                    switch ($movimiento->tipo_st) {
                        case 'd':
                        case 'g':
                            // ACCIONES SI EL TIPO DE LA SOLICITUD ES DEPOSITO
                            $currentCuenta->sum_dep -= $movimiento->ingreso;
                            $currentCuenta->monto_total -= $movimiento->ingreso;
                            $currentCuenta->no_dep -= 1;
                            break;
                        case 'b':
                            $currentCuenta->monto_total -= $movimiento->ingreso;
                            break;
                        case 'r':
                        case 'p':
                            // ACCIONES SI EL TIPO DE LA SOLICITUD ES RETIRO
                            if ($currentCuenta->monto_total < $movimiento->ingreso) {
                                DB::rollBack();
                                return Helpers::sendErrorNotification('No se puede revertir el movimiento porque la cuenta no cuenta con fondos suficientes.');
                            }
                            $currentCuenta->sum_retiros -= $movimiento->ingreso;
                            $currentCuenta->monto_total += $movimiento->ingreso;
                            $currentCuenta->no_retiros -= 1;
                            break;
                    }

                    $currentCuenta->update();

                    DB::commit();

                    $this->est_st = 'c';
                    $this->update();

                    return Helpers::sendWarningNotification(
                        'La cuenta se restauro a su estado anterior',
                        'Este movimiento fue revertido exitosamente'
                    );
                }
                // ACCIONES FUTURAS PARA NOTIFICAR POR EMAIL LA RAZON DEL RECHAZO
                // $this->razon_rechazo = 'El movimiento fue rechazado por el administrador';
            }

            $this->est_st = 'c';
            $this->update();

            return Helpers::sendErrorNotification(
                'Por favor, contacte al administrador para mas informacion',
                'Este movimiento fue rechazado'
            );
        }
    }

    public function cuentaCliente(): BelongsTo
    {
        return $this->belongsTo(CuentaCliente::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
