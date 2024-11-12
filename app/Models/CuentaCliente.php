<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CuentaCliente extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'billetera',
        'divisa',
        'monto_total',
        'ultimo_movimiento',
        'suma_total_depositos',
        'no_depositos',
        'suma_total_retiros',
        'no_retiros',
        'cliente_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'monto_total' => 'decimal:2',
        'ultimo_movimiento' => 'datetime',
        'suma_total_depositos' => 'decimal:2',
        'suma_total_retiros' => 'decimal:2',
        'cliente_id' => 'integer',
    ];

    public static function saveRecordFromUserId($data): void
    {
        CuentaCliente::create([
            'billetera' => $data['billetera'],
            'divisa' => $data['divisa'],
            'cliente_id' => $data['id'],
        ]);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}
