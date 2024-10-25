<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cliente extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identificacion',
        'nombre',
        'direccion',
        'genero',
        'celular',
        'fecha_nacido',
        'fecha_sistema',
        'estado',
        'promocion',
        'codigo_unico',
        'ciudad',
        'infoeeuu',
        'caso',
        'tipo_doc_subm',
        'activo_subm',
        'metodoPago',
        'cod_postal',
        'pais',
        'monto_pag',
        'doc_soporte',
        'archivo_soporte',
        'comprobante_pag',
        'estado_cliente',
        'fase_cliente',
        'origenes',
        'billetera',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'fecha_nacido' => 'date',
        'fecha_sistema' => 'datetime',
        'monto_pag' => 'decimal:2',
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notas(): HasMany
    {
        return $this->hasMany(Notas::class);
    }

    public function asignacion(): HasOne
    {
        return $this->hasOne(Asignacion::class);
    }
}
