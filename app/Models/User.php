<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Forms;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
                            Forms\Components\Section::make('Información de inicio de sesión')
                                ->schema([

                                    /**
                                     *
                                     *  INFORMACION DE INICIO DE SESIÓN
                                     *
                                     */
                                    Forms\Components\Select::make('roles')
                                        ->label('Asignar rol')
                                        ->relationship('roles', 'name')
                                        ->multiple()
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
                                                ->required()
                                                ->revealable()
                                                ->minLength(6),
                                        ]),
                                ]),
                        ]),
                    Forms\Components\Tabs\Tab::make('Perfil')
                        ->schema([
                            /**
                             *
                             *  INFORMACION DEL CLIENTE RELACIONADA DIRECTAMENTE A ESTE USUARIO
                             *
                             */
                            Forms\Components\Fieldset::make('cliente')
                                ->relationship('cliente')
                                ->label('Datos personales y de pago')
                                ->schema(Cliente::getForm()),
                        ])
                ])
        ];
    }

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class);
    }
}
