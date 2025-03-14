<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\Helpers;
use Filament\Forms;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return Helpers::isAsesor() || Helpers::isSuperAdmin();
        }

        return true;
    }

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
                        ])
                        ->visible(function(){
                            if(Helpers::isSuperAdmin()){
                                return true;
                            }
                        }),
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
                                ->label('Informacion personal del cliente')
                                ->schema(Cliente::getForm())
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
                        ])->visible(function(){
                            if(Helpers::isSuperAdmin()){
                                return true;
                            }
                        }),
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

    public function assignNewAsesor($asesor_id): void
    {
        # I need to check if the Asignacion is already registered. Otherwise, I will create a new one.
        $asignacion = Asignacion::where('user_id', $this->id)->first();

        if ($asignacion) {
            $asignacion->update([
                'asesor_id' => $asesor_id,
                'estado_asignacion' => true,
                'updated_at' => now(),
            ]);
            
            return;
        }

        Asignacion::create([
            'user_id' => $this->id,
            'asesor_id' => $asesor_id,
            'estado_asignacion' => true,
        ]);
    }

    public function assingNewFase($newFase): void
    {
        $cliente = Cliente::where('user_id', $this->id)->first();
        if($cliente){
            $cliente->update([                    
                'fase_cliente' => $newFase,               
            ]);
        }      
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
        )->latest();
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
            $user->Seguimientos()->delete();
            $user->asignacion()->delete();
            $user->asesor()->delete();
        });
    }
}
