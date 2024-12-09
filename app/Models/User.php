<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Forms;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function getForm(): array
    {
        return [
            Forms\Components\Tabs::make('Algo')
                ->columnSpanFull()
                ->schema([
                    Forms\Components\Tabs\Tab::make('Usuario')
                        ->schema([
                            /**
                             *
                             *  INFORMACION DE INICIO DE SESIÓN
                             *
                             */
                            Forms\Components\Select::make('roles')
                                ->label('Asignar rol')
                                ->relationship('roles', 'name')
                                ->preload()
                                ->searchable(),
                            Forms\Components\Grid::make()
                                ->columns(3)
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Usuario')
                                        ->required(),
                                    Forms\Components\TextInput::make('email')
                                        ->label('Correo electrónico')
                                        ->email()
                                        ->required()
                                        ->prefix('@'),
                                    Forms\Components\TextInput::make('password')
                                        ->label('Contraseña')
                                        ->default('Aa123456')
                                        ->helperText('La contraseña debe tener más de 6 caracteres.')
                                        ->password()
                                        ->dehydrated(fn($state) => filled($state))
                                        ->required(fn(string $context): bool => $context === 'create')
                                        ->minLength(6),
                                ]),
                        ]),
                    Forms\Components\Tabs\Tab::make('Perfil')
                        ->schema([
                            /**
                             *
                             *  INFORMACION DEL CLIENTE RELACIONADA
                             *  DIRECTAMENTE A ESTE USUARIO
                             *
                             */
                            Forms\Components\Fieldset::make('cliente')
                                ->relationship('cliente')
                                ->label('Datos personales y de pago')
                                ->schema(Cliente::getForm()),
                        ]),
                    Forms\Components\Tabs\Tab::make('Cuenta del cliente')
                        ->schema([
                            /**
                             *
                             *  INFORMACION DE LA CUENTA DEL CLIENTE
                             *  RELACIONADA DIRECTAMENTE A ESTE USUARIO
                             *
                             */
                            Forms\Components\Fieldset::make('cuentaCliente')
                                ->relationship('cuentaCliente')
                                ->label('La cuenta, el lugar donde se almacena el deposito y los movimientos')
                                ->schema(CuentaCliente::getForm()),
                        ]),
                ])
        ];
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class);
    }

    public function asignacion(): HasOne
    {
        return $this->hasOne(Asignacion::class);
    }

    public function asesor(): HasOne
    {
        return $this->hasOne(Asesor::class);
    }

    public function asesorUser(): HasOne
    {
        return $this->hasOne(Asesor::class);
    }

    public function cuentaCliente(): HasOne
    {
        return $this->hasOne(CuentaCliente::class);
    }

    public function cuentaMovimientos(): HasManyThrough
    {
        return $this->hasManyThrough(
            Movimiento::class,
            CuentaCliente::class,
            'user_id', // cuenta_clientes.user_id
            'cuenta_cliente_id', // movimientos.cuenta_cliente
            'id', // user.id
            'id' // cuenta_cliente.id
        );
    }

    public function seguimientos(): HasMany
    {
        return $this->hasMany(Seguimiento::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->cliente()->delete();
            $user->cuentaCliente()->delete();
        });
    }
}
