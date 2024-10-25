<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fondo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_solicitud_f',
        'id_propietario',
        'tipo_solicitud',
        'estado',
        'sistema_pago',
        'fecha_creado',
        'deposito',
        'divisa',
        'motivo_rechazo',
        'comprobante',
        'radicado',
        'billetera',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'id_solicitud_f' => 'integer',
        'id_propietario' => 'integer',
        'fecha_creado' => 'datetime',
        'deposito' => 'decimal:2',
    ];

    public function idSolicitudF(): BelongsTo
    {
        return $this->belongsTo(IdSolicitudF::class);
    }

    public function idPropietario(): BelongsTo
    {
        return $this->belongsTo(IdPropietario::class);
    }
}
