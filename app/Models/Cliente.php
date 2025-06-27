<?php

namespace App\Models;

use App\Enums\RegisterCuestionaryOptions;
use App\Helpers\Helpers;
use App\Helpers\OptionsHelper;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country as CountryField;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Cliente extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

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
        'is_activo',

        'estado_cliente',
        'fase_cliente',

        'origenes',

        'infoeeuu',
        'caso',

        'tipo_doc_id',
        'file_id',

        'est_docs',

        'tipo_doc_soporte',
        'file_soporte',

        'comprobante_pag',
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
        'is_activo' => 'boolean',
        'user_id' => 'integer',
    ];

    public static function getForm(): array
    {
        return
            [
                Tabs::make('Informacion del cliente')
                    ->columnSpanFull()
                    ->schema([
                        Tabs\Tab::make('Informacion Personal')
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('nombre_completo')
                                    ->maxLength(150)
                                    ->default(null)
                                    ->readOnly(fn () => Helpers::isAsesor()),
                                Forms\Components\TextInput::make('identificacion')
                                    ->maxLength(50)
                                    ->default(null)
                                    ->readOnly(fn () => Helpers::isAsesor()),
                                Forms\Components\DatePicker::make('fecha_nacimiento')
                                    ->native()
                                    ->disabled(fn () => Helpers::isAsesor()),
                                Forms\Components\Select::make('genero')
                                    ->options([
                                        'm' => 'Masculino',
                                        'f' => 'Femenino',
                                    ])
                                    ->disabled(fn () => Helpers::isAsesor()),
                                CountryField::make('pais')
                                        ->label('País')
                                        ->searchable()
                                        ->required()
                                        ->options(fn () => collect((new CountryField('pais'))->getCountriesList())
                                            // Volteamos el array para que clave = nombre, valor = nombre
                                            ->mapWithKeys(fn (string $name) => [ $name => $name ])
                                            ->toArray()
                                        )
                                        ->afterStateHydrated(function (CountryField $component, ?string $state) {
                                            // Si el estado actual es un ISO (CO, MX…), lo convertimos a nombre
                                            $map = (new CountryField('pais'))->getCountriesList(); // ['CO'=>'Colombia', …]
                                            if ($state && isset($map[$state])) {
                                                $component->state($map[$state]);
                                            }
                                        }),
                                Forms\Components\TextInput::make('ciudad')
                                    ->maxLength(50)
                                    ->default(null)
                                    ->readOnly(fn () => Helpers::isAsesor()),
                                Forms\Components\TextInput::make('direccion')
                                    ->maxLength(250)
                                    ->default(null)
                                    ->readOnly(fn () => Helpers::isAsesor()),
                                Forms\Components\TextInput::make('cod_postal')
                                    ->maxLength(50)
                                    ->default(null)
                                    ->readOnly(fn () => Helpers::isAsesor()),
                                Forms\Components\TextInput::make('celular')
                                    ->maxLength(25)
                                    ->default(null)
                                    ->readOnly(fn () => Helpers::isAsesor()),
                                Forms\Components\TextInput::make('telefono')
                                    ->tel()
                                    ->maxLength(25)
                                    ->default(null),
                                ]),
                        Tabs\Tab::make('Informacion de Marketing')
                            ->schema([
                                Forms\Components\Section::make('Informacion de seguimientos')
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\Toggle::make('is_activo')
                                            ->visible(fn() => Helpers::isSuperAdmin())
                                            ->helperText('Estado actual de la asignacion')
                                            ->label(function ($state) {
                                                return $state ? 'Activo' : 'Inactivo';
                                            })
                                            ->live()
                                            ->default(1),
                                        Forms\Components\Select::make('estado_cliente')
                                            ->options(OptionsHelper::estadoOptions())
                                            ->preload()
                                            ->createOptionForm([
                                                TextInput::make('estado_cliente')
                                                    ->label('Nuevo estatus')
                                                    ->required()
                                                    ->placeholder('Escribe el nuevo estado')
                                                ])
                                            ->createOptionUsing(function (array $data) {
                                                $nuevoValor = $data['estado_cliente'];
                                                OptionsHelper::createOptions('estado_cliente', $nuevoValor);
                                                return $nuevoValor;
                                            }),
                                        Forms\Components\Select::make('fase_cliente')
                                            ->options(OptionsHelper::faseOptions())
                                            ->preload()
                                            ->createOptionForm([
                                                TextInput::make('fase_cliente')
                                                    ->label('Nueva fase')
                                                    ->required()
                                                    ->placeholder('Escribe la nueva fase')
                                                ])
                                            ->createOptionUsing(function (array $data) {
                                                $nuevoValor = $data['fase_cliente'];
                                                OptionsHelper::createOptions('fase_cliente', $nuevoValor);
                                                return $nuevoValor;
                                            }),
                                        Forms\Components\Select::make('origenes')
                                            ->options(OptionsHelper::origenOptions())
                                            ->preload()
                                            ->createOptionForm([
                                                TextInput::make('origenes')
                                                    ->label('Nuevo origen')
                                                    ->required()
                                                    ->placeholder('Escribe el nuevo origen')
                                                ])
                                            ->createOptionUsing(function (array $data) {
                                                $nuevoValor = $data['origenes'];
                                                OptionsHelper::createOptions('origenes', $nuevoValor);
                                                return $nuevoValor;
                                            }),
                                    ]),
                            ])
                            ->visible(fn () => auth()->user()->hasRole('cliente') === false),
                        Tabs\Tab::make('Cuestionario')
                            ->columns(2)
                            ->schema([
                                Forms\Components\Radio::make('infoeeuu')
                                ->label('¿Soy una persona de la que hay que informar en Estados Unidos?')
                                ->boolean()
                                ->inline()
                                ->inlineLabel(false),
                            Forms\Components\Radio::make('caso')
                                ->label('Indica cual es tu caso:')
                                ->inline()
                                ->inlineLabel(false)
                                ->options([
                                    'a' => RegisterCuestionaryOptions::OPTION_A->value,
                                    'b' => RegisterCuestionaryOptions::OPTION_B->value,
                                    'c' => RegisterCuestionaryOptions::OPTION_C->value,
                                    'd' => RegisterCuestionaryOptions::OPTION_D->value,
                                ]),
                            ])
                            ->visible(function(){
                                if(Helpers::isSuperAdmin()){
                                    return true;
                                }
                            }),
                        Tabs\Tab::make('Documentos subidos')
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('tipo_doc_id')
                                            ->datalist([
                                                'DNI',
                                                'PASAPORTE'
                                            ])
                                            ->default(null),
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('file_id')
                                            ->collection('users_id_documents')
                                            ->default(null),
                                        Forms\Components\TextInput::make('tipo_doc_soporte')
                                            ->datalist([
                                                'SERVICIOS PUBLICOS',
                                            ])
                                            ->default(null),
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('file_soporte')
                                            ->collection('users_support_documents')
                                            ->default(null),
                                    ]),
                                Forms\Components\SpatieMediaLibraryFileUpload::make('comprobante_pag')
                                    ->collection('clientes_payment_files'),
                            ])
                            ->visible(function () { return Helpers::isSuperAdmin() || auth()->user()->hasRole('cliente'); })
                    ])
            ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // En app/Models/Cliente.php
    protected static function booted()
    {
        static::updating(function ($cliente) {
            // Si el cliente ya existe y se está actualizando
            if ($cliente->exists) {
                // Reducir el contador solo si es mayor que 0
                if ($cliente->contador_ediciones > 0) {
                    $cliente->contador_ediciones--;
                }
            }
        });
    }
    }
