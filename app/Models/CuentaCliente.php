<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Forms;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuentaCliente extends Model
{
    use HasFactory, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sistema_pago',
        'billetera',
        'divisa',
        'estado_cuenta',
        'ultimo_movimiento_id',
        'monto_total',
        'sum_dep',
        'no_dep',
        'sum_retiros',
        'no_retiros',
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'estado_cuenta' => 'boolean',
        'ultimo_movimiento_id' => 'integer',
        'monto_total' => 'decimal:3',
        'sum_dep' => 'decimal:3',
        'sum_retiros' => 'decimal:3',
        'user_id' => 'integer',
    ];

    public static function getForm()
    {
        return [
            Forms\Components\Grid::make()
                ->columns(3)
                ->schema([
                    Forms\Components\Textarea::make('billetera')
                        // ->default('fr-')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('sistema_pago')
                        ->maxLength(255)
                        ->default(null),
                    Forms\Components\TextInput::make('divisa')
                        ->maxLength(15)
                        ->default(null),
                        Forms\Components\Toggle::make('estado_cuenta')
                        ->helperText('Estado actual de la cuenta')
                        ->label(function ($state) {
                            return $state ? 'Activa' : 'Inactiva';
                        })
                        ->live()
                        ->default(1),
                ]),
            // Forms\Components\Select::make('user_id')
            //     ->relationship('user', 'name')
            //     ->required(),
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(Movimiento::class);
    }
}
