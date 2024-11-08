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
        'nombre_completo',
        'identificacion',
        'fecha_nacimiento',
        'genero',
        'pais',
        'ciudad',
        'direccion',
        'cod_postal',
        'celular',
        'telefono',
        'estado',
        'promocion',
        'estado_cliente',
        'fase_cliente',
        'origenes',
        'infoeeuu',
        'caso',
        'tipo_doc_subm',
        'activo_subm',
        'metodo_pago',
        'doc_soporte',
        'archivo_soporte',
        'comprobante_pag',
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
        'fecha_nacimiento' => 'date',
        'estado' => 'boolean',
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function seguimientos(): HasMany
    {
        return $this->hasMany(Seguimiento::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(Movimiento::class);
    }

    public function asignacion(): HasOne
    {
        return $this->hasOne(Asignacion::class);
    }

    public function cuentaCliente(): HasOne
    {
        return $this->hasOne(CuentaCliente::class);
    }
}
