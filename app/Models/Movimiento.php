<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimiento extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_propietario',
        'nombre',
        'monto_ingreso',
        'monto_total',
        'fecha_actualizacion',
        'total_depositos',
        'total_retiros',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_propietario' => 'integer',
        'monto_ingreso' => 'decimal:2',
        'monto_total' => 'decimal:2',
        'fecha_actualizacion' => 'datetime',
        'total_depositos' => 'decimal:2',
        'total_retiros' => 'decimal:2',
    ];

    public function idPropietario(): BelongsTo
    {
        return $this->belongsTo(IdPropietario::class);
    }
}
