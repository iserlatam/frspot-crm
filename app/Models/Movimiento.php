<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
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

    public function chargeAccount(array $body) {
        [$case, $record] = $body;
        $currentCuenta = CuentaCliente::findOrFail($record->cuenta_cliente_id);

        // Movimiento aprobado
        if ($case === 'a'){
            $currentCuenta->monto_total += $record->ingreso;
            $currentCuenta->no_dep += 1;
            $currentCuenta->sum_dep += $currentCuenta->sum_dep;
            // $currentCuenta->
            // $currentCuenta->
            $currentCuenta->update();
        }
        // Movimiento rechazado
        else if ($case === 'c') {
            $currentCuenta->monto_total -= $record->ingreso;
            $currentCuenta->update();
        }
    }

    public function cuentaCliente(): BelongsTo
    {
        return $this->belongsTo(CuentaCliente::class);
    }
}
