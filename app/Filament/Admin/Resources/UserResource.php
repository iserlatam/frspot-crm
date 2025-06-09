<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Filament\Admin\Resources\UserResource\RelationManagers\AsignacionRelationManager;
use App\Filament\Admin\Resources\UserResource\RelationManagers\CuentaClienteRelationManager;
use App\Filament\Admin\Resources\UserResource\RelationManagers\SeguimientosRelationManager;
use App\Filament\Admin\Resources\UserResource\RelationManagers\UserMovimientosRelationManager;
use App\Helpers\Helpers;
use App\Helpers\OptionsHelper;
use App\Mail\WelcomeUserEmail;
use App\Models\User;
use Attribute;
use BaconQrCode\Renderer\Color\Gray;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use NunoMaduro\Collision\Adapters\Phpunit\State;

use function Laravel\Prompts\search;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $activeNavigationIcon = 'heroicon-s-users';

    protected static ?string $navigationGroup = 'Control de usuarios';

    protected static ?string $modelLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form->schema(User::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table

            ->query(function () {
                // UTILIZAR LAZY HACE QUE NO SE BUSQUEN TODOS LOS REGISTROS SINO UN LOTE DE 1000
                // "WITH" PRECARGA LAS RELACIONES QUESE VAN A UTILIZAR REPETIDAMENTE
                $query = User::query()
                    ->with([
                        // Campos de la relación cliente
                        'cliente',
                        // Campos de la relación asignacion
                        'asignacion.asesor.user',
                        'asignacion',
                        // Campos de la relacion roles
                        'roles:id,name',
                    ])
                    ->select(
                        [
                            // Campos del modelo
                            'id',
                            'name',
                            'email',
                            'created_at',
                            'updated_at',
                        ]
                    )
                    ->where('email', 'NOT LIKE', '%@frspot.com'); // Excluir emails que terminan en @frspot.com

                // ESTO NO ES NECESARIO YA QUE EL SUPER ADMIN SIEMPRE PUEDE VER A TODOS SUS USUARIOS
                // REDUCE LOS TIEMPOS DE CARGA AL INICIAR EL MODULO @USERS
                // $query->whereHas('roles', function ($query) {
                //     $query->where('name', 'cliente');
                // });

                // EL ACCESOR TIENE SOLO LOS REGISTROS RELACIONADOS A EL
                // NO ES NECESARIO HACER OTRO whereHas YA QUE TODO SE PUEDE ANIDAR
                // UTILIZAMOS LAZY AL FINAL PARA QUE NO SE CARGUEN TODOS LOS REGISTROS AL MISMO TIEMPO SINO SOLO LOS NECESARIOS
                if (Helpers::isAsesor()) {
                    $query->whereHas('asignacion.asesor.user', function ($query) {
                        $query->where('id', auth()->user()->id)->lazy();
                    });
                }

                /*
                ##
                ##  Logica para separar los clientes por oficinas segun el tipo de asesor asignado al cliente
                ## - se agrega que crm junior no pueda ver los clientes de retencion
                ##
                */

                if (Helpers::isTeamFTD() || Helpers::isCrmJunior()) {
                    $query->whereHas('asignacion.asesor.user', function ($query) {
                        $query->where('tipo_asesor', '=', 'ftd')->lazy();
                    });
                } elseif (Helpers::isTeamRTCN()) {
                    $query->whereHas('asignacion.asesor.user', function ($query) {
                        $query->where('tipo_asesor', '=', 'retencion')->lazy();
                    });
                }

                return $query;
            })
            ->defaultSort(Helpers::isSuperAdmin() ? 'created_at' : 'cliente.updated_at', Helpers::isSuperAdmin() ? 'desc' : 'asc')

            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('usuario')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->copyable()
                    ->limit(fn() => Helpers::isSuperAdmin() ? 6 : 15)
                    ->tooltip(function ($record): ?string {
                        return $record->name;
                    }),
                Tables\Columns\TextColumn::make('email')
                    ->formatStateUsing(fn($record) => Helpers::isSuperAdmin() ? $record->email : '*****@*****.***')
                    ->copyable()
                    ->copyableState(fn($record) => $record->email)
                    ->tooltip('Haga click para copiar')
                    ->limit(fn() => Helpers::isSuperAdmin() ? 6 : 15)
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.celular')->label('Celular')
                    ->formatStateUsing(fn($record) => Helpers::isSuperAdmin() ? $record->cliente?->celular : '********')
                    ->tooltip('Haga click para copiar')
                    ->sortable()
                    ->copyable()
                    ->copyableState(fn($record) => $record->cliente?->celular)
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.pais')
                    ->label('pais')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.estado_cliente')
                    ->label('Estado cliente')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Active' => 'success',
                        'Deposit' => 'success',
                        'Potential' => 'danger',
                        'Declined' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('cliente.fase_cliente')
                    ->label('Fase actual')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Active' => 'success',
                        'Deposit' => 'success',
                        'Potential' => 'danger',
                        'Declined' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('asignacion.asesor.user.name')
                    ->label('Asesor asignado'),
                Tables\Columns\TextColumn::make('cliente.origenes')
                    ->label('Origen cliente')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.updated_at')
                    ->date('M d/Y h:i A')
                    ->label('ultima actualización')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('M d/y h:i A')
                    ->label('Fecha de creacion')
                    ->sortable(),
                Tables\Columns\TextColumn::make('asignacion.estado_asignacion')
                    ->label('Estado asignacion')
                    ->badge()
                    ->searchable()
                    ->color(function ($state) {
                        return match ($state) {
                            true => 'success',
                            false => 'danger',
                        };
                    })
                    ->formatStateUsing(function ($state) {
                        return $state ? 'Activa' : 'Inactiva';
                    }),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Rol asignado')
                    ->sortable(),
                Tables\Columns\TextColumn::make('asignacion.id')->label('asignacion id'),
            ])
            //inicio filtros
            ->filters([
                // 📌 Filtro por Asesor Asignado
                // OK
                SelectFilter::make('asignacion.asesor.user.name')
                    ->relationship('asignacion.asesor', 'id')
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->user->name)
                    ->searchable()
                    ->preload()
                    ->label('Asesor asignado'),

                // OK
                Tables\Filters\Filter::make('sin_asignacion')
                    ->label('Sin asignacion')
                    ->toggle()
                    ->query(function (Builder $query): Builder {
                        return $query->whereDoesntHave('asignacion');
                    }),

                Tables\Filters\Filter::make('Filtros de marketing')
                    ->form([
                        Forms\Components\Grid::make(2) // 📌 Organiza los filtros en 2 columnas
                            ->schema([
                                Forms\Components\Select::make('estado_cliente')
                                    ->label('Estado cliente')
                                    ->options(OptionsHelper::estadoOptions())
                                    ->placeholder('Selecciona un estado'),

                                Forms\Components\Select::make('fase_cliente')
                                    ->label('Fase cliente')
                                    ->options(OptionsHelper::faseOptions())
                                    ->placeholder('Selecciona una fase'),

                                Forms\Components\TextInput::make('pais')
                                    ->label('Pais')
                                    ->placeholder('Escribe el pais del cliente')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->with(['cliente'])
                            ->when($data['estado_cliente'] ?? null, function ($query, $value) {
                                $query->whereHas('cliente', fn($q) => $q->where('estado_cliente', $value))->lazy();
                            })
                            ->when($data['fase_cliente'] ?? null, function ($query, $value) {
                                $query->whereHas('cliente', fn($q) => $q->where('fase_cliente', $value))->lazy();
                            })
                            ->when($data['pais'] ?? null, function ($query, $value) {
                                $query->whereHas('cliente', fn($q) => $q->where('pais', 'like', "%$value%"))->lazy();
                            });
                    }),

                // FILTRAR POR ROLE DE CLIENTE => SE USA UN SELECT CON RELATIONSHIP ROLES
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->label('Rol asignado'),

                // 📌 FILTRO DE FECHAS
                Tables\Filters\Filter::make('Filtro de Fechas')
                    ->form([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('fecha_inicio')
                                    ->label('Fecha desde')
                                    ->placeholder('Selecciona una fecha')
                                    ->native(false),

                                Forms\Components\DatePicker::make('fecha_fin')
                                    ->label('Fecha hasta')
                                    ->placeholder('Selecciona una fecha')
                                    ->native(false),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['fecha_inicio'] ?? null,
                                fn($query, $value) =>
                                $query->whereDate('created_at', '>=', $value)
                            )
                            ->when(
                                $data['fecha_fin'] ?? null,
                                fn($query, $value) =>
                                $query->whereDate('created_at', '<=', $value)
                            );
                    }),
            ])
            ->deferFilters()
            //fin filtros

            ->actions([Tables\Actions\EditAction::make()->iconButton()->tooltip('Editar usuario')], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                /**
                 *  ASIGNACION MASIVA
                 */
                BulkAction::make('Asignar asesor')
                    ->color('primary')
                    ->icon('heroicon-s-identification')
                    ->form([
                        Select::make('asesor_id')
                            ->relationship('asesor', 'id') // Define la relación y la clave foránea
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->user->name ?? 'Sin nombre')
                            ->default(null),
                    ])
                    ->action(function (array $data, Collection $records) {
                        $records->each->assignNewAsesor($data['asesor_id']);
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                        Notification::make()->title('Asesor asignado con éxito')->success()->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->visible(function () {
                        if (Helpers::isSuperAdmin() || Helpers::isCrmManager()) {
                            return true;
                        }
                    }),
                /**
                 *  ASIGNAR ROL CLIENTE
                 */
                BulkAction::make('Asignar nuevo estado')
                    ->color('info')
                    ->icon('heroicon-s-arrows-right-left')
                    ->form([Select::make('estado_cliente')->options(OptionsHelper::estadoOptions())->required()])
                    ->action(function (array $data, Collection $records) {
                        $records->each(function ($user) use ($data) {
                            $user?->cliente->update(['estado_cliente' => $data['estado_cliente']]);
                        });
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                        Notification::make()->title('estado actualizado con éxito')->success()->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->visible(function () {
                        if (Helpers::isCrmManager() || Helpers::isSuperAdmin()) {
                            return true;
                        }
                    }),
                /**
                 *  ASIGNAR FASE
                 */
                BulkAction::make('assignar nueva fase')
                    ->color('success')
                    ->icon('heroicon-s-arrow-path')
                    ->form([Select::make('fase')->label('Fase del cliente')->options(OptionsHelper::faseOptions())->required()])
                    ->action(function (array $data, Collection $records): void {
                        $records->each(function ($user) use ($data) {
                            $user->assingNewFase($data['fase']);
                        });
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                        Notification::make()->title('Fase actualizado con éxito')->success()->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->visible(function () {
                        if (Helpers::isSuperAdmin() || Helpers::isCrmManager()) {
                            return true;
                        }
                        return false;
                    }),

                /**
                 *  ASIGNAR ORIGEN
                 */
                BulkAction::make('Asignar nuevo origen')
                    ->color('warning')
                    ->icon('heroicon-s-globe-americas')
                    ->form([
                        Select::make('origenes')
                            ->options(OptionsHelper::getOptions('origenes'))
                            ->required(),
                    ])
                    ->action(function (array $data, Collection $records) {
                        $records->each(function ($user) use ($data) {
                            $user?->cliente->update(['origenes' => $data['origenes']]);
                        });
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                        Notification::make()->title('Origenes actualizados con éxito')->success()->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->visible(function () {
                        if (Helpers::isSuperAdmin() || Helpers::isCrmManager()) {
                            return true;
                        }
                    }),
                /**
                 *  ACCIONES DE ELIMINACION DE USUARIOS
                 */
                Tables\Actions\DeleteBulkAction::make('delete')->visible(fn() => Helpers::isSuperAdmin()),
                /**
                 *  ENVIAR EMAIL DE BIENVENIDAD
                 */
                BulkAction::make('Enviar mensaje de bienvenida')
                    ->color('primary')
                    ->icon('heroicon-o-envelope')
                    // ->form([
                    //     Select::make('origenes')
                    //         ->options(OptionsHelper::getOptions('origenes'))
                    //         ->required(),
                    // ])
                    ->action(function (array $data, Collection $records) {
                        $records->each(function ($user) use ($data) {
                            Mail::to($user)->send(new WelcomeUserEmail($user));
                        });
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                        Notification::make()
                            ->title('Mensajes de bienvenida enviados con exito')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->visible(function () {
                        if (Helpers::isSuperAdmin() || Helpers::isCrmManager() || Helpers::isTeamFTD() || Helpers::isTeamRTCN()) {
                            return true;
                        }
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Cuentas y movimientos
            RelationGroup::make('Cuentas y movimientos', [UserMovimientosRelationManager::class, CuentaClienteRelationManager::class])->icon('heroicon-m-arrows-up-down'),
            // Asignaciones
            RelationGroup::make('Comentarios', [AsignacionRelationManager::class, SeguimientosRelationManager::class])->icon('heroicon-m-arrows-right-left'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
