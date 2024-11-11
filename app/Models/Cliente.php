<?php

namespace App\Models;

use Filament\Forms;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

    public static function getForm(): array
    {
        return [
            Forms\Components\Section::make('Informacion Personal')
                ->description('Añade al informacion personal de nuevo usuario')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('nombre_completo')
                        ->maxLength(150)
                        ->default(null),
                    Forms\Components\TextInput::make('identificacion')
                        ->maxLength(50)
                        ->default(null),
                    Forms\Components\DatePicker::make('fecha_nacimiento'),
                    Forms\Components\Select::make('genero')
                        ->options([
                            'm' => 'Masculino',
                            'f' => 'Femenino',
                        ]),
                    Forms\Components\TextInput::make('pais')
                        ->maxLength(50)
                        ->default(null),
                    Forms\Components\TextInput::make('ciudad')
                        ->maxLength(50)
                        ->default(null),
                    Forms\Components\TextInput::make('direccion')
                        ->maxLength(250)
                        ->default(null),
                    Forms\Components\TextInput::make('cod_postal')
                        ->maxLength(50)
                        ->default(null),
                    Forms\Components\TextInput::make('celular')
                        ->maxLength(25)
                        ->default(null),
                    Forms\Components\TextInput::make('telefono')
                        ->tel()
                        ->maxLength(25)
                        ->default(null),

                ]),

            Forms\Components\Section::make('Informacion de Marketing')
                ->description('Añada la informaicon de control del cliente')
                ->columns(2)
                ->schema([
                    Forms\Components\Section::make('informacion de seguimientos')
                        ->columns(3)
                        ->schema([
                            Forms\Components\Select::make('estado')
                                ->options([
                                    'New' => 'New',
                                    'No answer' => 'No answer',
                                    'Answer' => 'Answer',
                                    'Call again' => 'Call Again',
                                    'Potential' => 'Potential',
                                    'Low potential' => 'Low Potential',
                                    'Declined' => 'Declined',
                                    'Under age' => 'Under Age',
                                    'Active' => 'Active',
                                    'No interested' => 'No interested',
                                    'Invalid number' => 'Invalid number',
                                    'Stateless  '  => 'Stateless',
                                ]),
                            Forms\Components\Select::make('estado_cliente')
                                ->options([
                                    'New' => 'New',
                                    'No answer' => 'No answer',
                                    'Answer' => 'Answer',
                                    'Call again' => 'Call Again',
                                    'Potential' => 'Potential',
                                    'Low potential' => 'Low Potential',
                                    'Declined' => 'Declined',
                                    'Under age' => 'Under Age',
                                    'Active' => 'Active',
                                    'No interested' => 'No interested',
                                    'Invalid number' => 'Invalid number',
                                    'Stateless  '  => 'Stateless',
                                ]),

                            Forms\Components\TextInput::make('fase_cliente')
                                ->maxLength(50)
                                ->default(null),
                        ]),
                    Forms\Components\Grid::make('3')
                        ->schema([
                            Forms\Components\TextInput::make('promocion')
                                ->maxLength(1)
                                ->default(null),
                            Forms\Components\TextInput::make('origenes')
                                ->maxLength(60)
                                ->default(null),
                            Forms\Components\TextInput::make('infoeeuu')
                                ->maxLength(255)
                                ->default(null),
                        ]),
                    Forms\Components\Grid::make('3')
                        ->schema([
                            Forms\Components\TextInput::make('caso')
                                ->maxLength(20)
                                ->default(null),
                            Forms\Components\TextInput::make('tipo_doc_subm')
                                ->maxLength(50)
                                ->default(null),
                            Forms\Components\TextInput::make('activo_subm')
                                ->maxLength(250)
                                ->default(null),
                        ])
                ]),

            Forms\Components\Section::make('Información de Pago')
                ->description(function ($record): string {
                    if (url()->current() == route('filament.admin.resources.users.create')) {
                        return 'Añada la información mercantil del nuevo usuario';
                    }
                    return 'Edite la información mercantil del usuario';
                })
                ->schema([
                    Forms\Components\TextInput::make('metodo_pago')
                        ->maxLength(25)
                        ->default(null),
                    Forms\Components\TextInput::make('doc_soporte')
                        ->maxLength(50)
                        ->default(null),
                    Forms\Components\Textarea::make('archivo_soporte')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('comprobante_pag')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('billetera')
                        ->columnSpanFull(),
                ]),
        ];
    }

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
