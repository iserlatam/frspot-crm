<?php

namespace App\Models;

use App\Enums\RegisterCuestionaryOptions;
use App\Helpers\Helpers;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
                                    ->default(null),
                                Forms\Components\TextInput::make('identificacion')
                                    ->maxLength(50)
                                    ->default(null),
                                Forms\Components\DatePicker::make('fecha_nacimiento')
                                    ->native(),
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
                            ])
                            ->visible(function(){
                                if(Helpers::isSuperAdmin()){
                                    return true;
                                }
                            }),
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
                                                'interested'  => 'Interested',
                                            ]),
                                        Forms\Components\Select::make('fase_cliente')
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
                                                'Stateless'  => 'Stateless',
                                                'interested'  => 'Interested',
                                            ]),
                                        Forms\Components\Select::make('origenes')
                                            ->options([
                                                'AMZN' => 'AMZN',
                                                'AMZN200' => 'AMZN200',
                                                'AMZN280' => 'AMZN280',
                                                'BTC' => 'BTC',
                                                'PETROLEO' => 'PETROLEO',
                                                'APPLE' => 'APPLE',
                                                'CURSOS' => 'CURSOS',
                                                'PETROBLAS' => 'PETROBLAS',
                                                'XAUUSD' => 'XAUUSD',
                                                'TESLA' => 'TESLA',
                                                'INGRESOS_EXTRAS' => 'INGRESOS EXTRAS',
                                                'FRSPOT' => 'FRSPOT',
                                                'Conferencia_Musk' => 'Conferencia Musk',
                                                'COCA-COLA' => 'COCA-COLA',
                                                'ENTEL' => 'ENTEL',
                                                'BIMBO' => 'BIMBO',
                                            ]),
                                    ]),
                            ]),
                        Tabs\Tab::make('Cuestionario')
                            ->columns(2)
                            ->schema([
                                Forms\Components\Radio::make('infoeeuu')
                                ->label('¿Soy una persona de la que hay que informar en Estados Unidos?')
                                ->boolean()
                                ->inline()
                                ->inlineLabel(false)
                                ->required(),
                            Forms\Components\Radio::make('caso')
                                ->label('Indica cual es tu caso:')
                                ->inline()
                                ->inlineLabel(false)
                                ->options([
                                    'a' => RegisterCuestionaryOptions::OPTION_A->value,
                                    'b' => RegisterCuestionaryOptions::OPTION_B->value,
                                    'c' => RegisterCuestionaryOptions::OPTION_C->value,
                                    'd' => RegisterCuestionaryOptions::OPTION_D->value,
                                ])
                                ->required(),
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
                            ->visible(function(){
                                if(Helpers::isSuperAdmin()){
                                    return true;
                                }
                            }),
                    ])
            ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
