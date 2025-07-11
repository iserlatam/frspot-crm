<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SeguimientoResource\Pages;
use App\Filament\Admin\Resources\SeguimientoResource\RelationManagers;
use App\Helpers\Helpers;
use App\Helpers\OptionsHelper;
use App\Models\Asesor;
use App\Models\Seguimiento;
use App\Models\User;
use Exception;
use Faker\Extension\Helper;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Tables\Columns\Concerns\HasWidth;
use Illuminate\Database\Eloquent\Collection;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeguimientoResource extends Resource
{
    protected static ?string $model = Seguimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $activeNavigationIcon = 'heroicon-s-clipboard-document-list';

    protected static ?string $navigationGroup = 'Cuentas y movimientos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Seguimiento::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->query(
                function () {
                    $query = Seguimiento::query();

                    if (Helpers::isAsesor()) {
                        $query->whereHas('asesor.user', function ($query) {
                            $query->where('name', auth()->user()->name);
                        });
                    }

                    if(Helpers::isTeamFTD()) {
                        $query->whereHas('asesor.user', function ($query) {
                            $query->where('tipo_asesor','=','ftd')->lazy();
                        });
                    }
                    elseif(Helpers::isTeamRTCN()) {
                        $query->whereHas('asesor.user', function ($query) {
                            $query->where('tipo_asesor','=','retencion')->lazy();
                        });
                    }

                    return $query; // Siempre debe retornar una consulta válida
                }
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->sortable()
                    ->copyable()
                    ->tooltip(function($record){
                        return $record->user->name;
                    })
                    ->limit('20')
                    ->searchable(),
                    // ->extraAttributes(['style' => 'max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'])
                Tables\Columns\TextColumn::make('asesor.user.name')
                    ->label('Asesor')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('descripcion')
                    ->html()
                    ->limit(300)
                    ->wrap() // Habilita el envolvimiento básico
                    ->extraAttributes([
                        'class' => 'max-w-md break-words', // Limita el ancho máximo y fuerza el salto de palabras
                        'style' => 'white-space: pre-wrap; word-break: break-word; max-width: 400px; text-wrap: true; word-break: break-all; overflow-wrap: break-word;', // Control adicional con CSS
                    ]),
                Tables\Columns\TextColumn::make('user.cliente.estado_cliente')
                    ->label('Estado actual')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Active' => 'success',
                        'Deposit' => 'success',
                        'Potential' => 'danger',
                        'Declined' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('user.cliente.fase_cliente')
                    ->label('Fase Actual')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Active' => 'success',
                        'Deposit' => 'success',
                        'Potential' => 'danger',
                        'Declined' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('user.asignacion.asesor.user.name')
                    ->label('Asesor asignado'),
                Tables\Columns\TextColumn::make('etiqueta')
                    ->visible(false),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('M d/Y h:i A')
                    ->label('Creado el')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('seguimiento_filters')
                    ->form([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('created_at_day')
                                    ->label('Día de creación')
                                    ->columnSpanFull()
                                    ->displayFormat('d/m/Y'),
                                // 📌 Filtro por Estado del Cliente
                                Forms\Components\Select::make('estado_cliente')
                                    ->options(OptionsHelper::estadoOptions())
                                    ->label('Estado cliente'),
                                // 📌 Filtro por Fase del Cliente
                                Forms\Components\Select::make('fase_cliente')
                                ->options(OptionsHelper::faseOptions())
                                    ->label('Fase cliente'),
                                    Forms\Components\Select::make('asesor_name')
                                    ->label('Asesor asignado')
                                    ->options(fn() => \App\Models\User::whereHas('asesor')->pluck('name', 'name')) // Muestra solo asesores
                                    ->searchable()
                                    ->preload()
                                    ->columnSpanFull(),

                            ]),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when($data['created_at_day'] ?? null, fn ($query, $date) =>
                                    $query->whereDate('created_at', $date)
                                )
                                ->when($data['estado_cliente'] ?? null,fn ($query, $estado) => $query->whereHas('user.cliente', function ($query) use ($estado) {
                                    $query->where('estado_cliente', $estado);
                                }))
                                ->when($data['fase_cliente'] ?? null,fn ($query, $fase) => $query->whereHas('user.cliente', function ($query) use ($fase) {
                                    $query->where('fase_cliente', $fase);
                                }))
                                ->when($data['asesor_name'] ?? null, fn ($query, $asesorNombre) =>
                                $query->whereHas('asesor.user', function ($query) use ($asesorNombre) {
                                    $query->where('name', $asesorNombre);
                                }));
                        }),
                ])
                ->deferFilters()
            ->headerActions([
                Helpers::renderReloadTableAction(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('ver comentario')
                    ->iconPosition('before')
                    ->icon('heroicon-o-eye')
                    ->iconButton()
                    ->tooltip('Ver comentario')
                    ->color('success'),
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip('Editar comentario')
                    ->visible(fn () => helpers::isSuperAdmin())
            ], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                BulkAction::make('asignar nuevo estado')
                    ->color('warning')
                    ->icon('heroicon-s-identification')
                    ->form([
                        Select::make('estado_cliente')
                        ->label('Estado del cliente')
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
                            'Recovery'  => 'Recovery',
                            'Invalid number' => 'Invalid number',
                            'Stateless'  => 'Stateless',
                            'Interested'  => 'Interested',
                        ])
                        ->required(),
                    ])
                    ->action(function(array $data, Collection $records): void {
                        // Validación del campo fase_cliente
                        if (!isset($data['estado_cliente']) || empty($data['estado_cliente'])) {
                            throw new Exception('El campo estado_cliente no tiene valor.');
                        }

                        // Actualizar fase de cada cliente
                        $records->each(function ($seguimiento) use ($data) {
                            // Obtener el cliente a través del usuario
                            $cliente = $seguimiento->user?->cliente;

                            if (!$cliente) {
                                throw new Exception('No se encontró un cliente asociado a este seguimiento.');
                            }

                            // Actualizar la fase del cliente
                            $cliente->update(['estado_cliente' => $data['estado_cliente']]);
                        });
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                        Notification::make()
                            ->title('estado actualizado con éxito')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->visible(fn()=>Helpers::isSuperAdmin()),
                BulkAction::make('asignar nueva fase')
                    ->color('success')
                    ->icon('heroicon-s-arrow-path')
                    ->form([
                        Select::make('fase_cliente')
                        ->label('Fase del cliente')
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
                            'Recovery'  => 'Recovery',
                            'Invalid number' => 'Invalid number',
                            'Stateless'  => 'Stateless',
                            'Interested'  => 'Interested',
                        ])
                        ->required(),
                    ])
                    ->action(function(array $data, Collection $records): void {
                        // Validación del campo fase_cliente
                        if (!isset($data['fase_cliente']) || empty($data['fase_cliente'])) {
                            throw new Exception('El campo fase_cliente no tiene valor.');
                        }

                        // Actualizar fase de cada cliente
                        $records->each(function ($seguimiento) use ($data) {
                            // Obtener el cliente a través del usuario
                            $cliente = $seguimiento->user?->cliente;

                            if (!$cliente) {
                                throw new Exception('No se encontró un cliente asociado a este seguimiento.');
                            }

                            // Actualizar la fase del cliente
                            $cliente->update(['fase_cliente' => $data['fase_cliente']]);
                        });
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                        Notification::make()
                            ->title('Fase actualizado con éxito')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->visible(fn()=>Helpers::isSuperAdmin()),
                Tables\Actions\DeleteBulkAction::make('delete')
                    ->visible(fn()=> Helpers::isSuperAdmin()),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeguimientos::route('/'),
            // 'create' => Pages\CreateSeguimiento::route('/create'),
            // 'edit' => Pages\EditSeguimiento::route('/{record}/edit'),
        ];
    }
}
