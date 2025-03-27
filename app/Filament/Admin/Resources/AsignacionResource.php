<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AsignacionResource\Pages;
use App\Filament\Admin\Resources\AsignacionResource\RelationManagers;
use App\Helpers\Helpers;
use App\Models\Asignacion;
use App\Models\CuentaCliente;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class AsignacionResource extends Resource
{
    protected static ?string $model = Asignacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $activeNavigationIcon = 'heroicon-s-arrows-right-left';

    protected static ?string $navigationLabel = 'Asignaciones';

    protected static ?string $breadcrumb = 'Asignaciones';

    protected static ?string $modelLabel = 'Asignacion';

    protected static ?string $pluralModelLabel = 'Asignaciones';

    protected static ?string $navigationGroup = 'Gestión de perfiles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('userWithRoleCliente', 'name')
                    ->label('Cliente a ser asignado')
                    ->searchable()
                    ->default(fn ($livewire) => $livewire instanceof RelationManager ? $livewire->ownerRecord->id : null) 
                    ->disabled(fn($livewire) => $livewire instanceof RelationManager)
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('asesor_id')
                ->relationship("asesor", 'id', function($query){
                    if(Helpers::isAsesor())
                        $query->where('id',auth()->user()->asesor->id);
                    }) // Define la relación y la clave foránea
                    ->searchable()
                    ->preload()
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->user->name)
                    ->default(null),
                Forms\Components\Toggle::make('estado_asignacion')
                    ->helperText('Estado actual de la asignacion')
                    ->label(function ($state) {
                        return $state ? 'Activo' : 'Inactivo';
                    })
                    ->live()
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('user.cliente.updated_at','desc')
            ->query(
                function () {
                    $query = Asignacion::query();
            
                    if (Helpers::isAsesor()) {
                        $query->whereHas('asesor.user', function ($query) {
                            $query->where('id', auth()->user()->id);
                        });
                    }
            
                    return $query; // Siempre debe retornar una consulta válida
                }
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID de asignacion')
                    ->copyable()
                    ->tooltip('haga click para copiar')
                    ->sortable()                   
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente asignado')
                    ->copyable()
                    ->sortable()
                    ->tooltip('Haga click para copiar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asesor.user.name')
                    ->label('Asesor asignado')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_asignacion')
                    ->label('Estado de la asignación')
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            true => 'success',
                            false => 'danger',
                        };
                    })
                    ->formatStateUsing(function ($state) {
                        return $state ? 'Activa' : 'Inactiva';
                    }),
                Tables\Columns\TextColumn::make('user.cliente.estado_cliente')
                    ->label('Estado cliente')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.cliente.fase_cliente')
                    ->label('Fase cliente')
                    ->sortable()
                    ->searchable(),
                    Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualización de asignación')
                    ->date('M d/Y h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.cliente.updated_at')
                    ->label('Última actualización cliente')
                    ->sortable()
                    ->date('M d/Y h:i A'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('fecha de creacion')
                    ->date('M d/Y h:i A')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('total_asignaciones')
                //     ->label('Cantidad de Asignaciones')
                //     ->getStateUsing(function ($record) {
                //         // Verifica si el registro tiene user_id y no es nulo
                //         if ($record->user_id) {
                //             return Asignacion::where('user_id', $record->user_id)->count();
                //         }
                //         return 0; // Devuelve 0 si no hay user_id
                //     })
                //     ->sortable(
                //         query: function (Builder $query, string $direction = 'asc') {
                //             return $query
                //                 ->select('asignacions.*')
                //                 ->selectRaw('(SELECT COUNT(*) FROM asignacions AS a WHERE a.user_id = asignacions.user_id) AS total_asignaciones_count')
                //                 ->orderBy('total_asignaciones_count', $direction);
                //         }
                //     )
                ])
            ->filters([
                // 📌 Filtro por Asesor Asignado
                SelectFilter::make('asesor.user.name')
                ->relationship('asesor', 'id')
                ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->user->name)
                ->preload()
                ->searchable()
                ->visible(fn()=>Helpers::isSuperAdmin())
                ->label('Asesor asignado'),
                
                Filter::make('asignacion_filtros')
                    ->form([
                        Forms\Components\DatePicker::make('updated_at_day')
                            ->label('Fecha de actualización')
                            ->displayFormat('d/m/Y'),
                            Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('cliente_estado_cliente')
                                    ->options(Helpers::getEstatusOptions())
                                    ->label('Estado cliente'),
                                // 📌 Filtro por Fase del Cliente
                                Forms\Components\Select::make('cliente_fase_cliente')
                                    ->options(Helpers::getEstatusOptions())
                                    ->label('Fase cliente'),                                                               
                            ])
                        ])
                                                   
                    ->query(function (Builder $query, array $data): Builder {                     
                        return $query
                            ->when($data['updated_at_day'] ?? null ,fn (Builder $query, $date): Builder => $query->whereDate('updated_at', $date)
                            )
                            ->when($data['cliente_estado_cliente'] ?? null , function ($query,$estado){
                                $query->whereHas('user.cliente', fn($query) => $query->where('estado_cliente', $estado));
                            })
                            ->when($data['cliente_fase_cliente'] ?? null , function ($query,$fase){
                                $query->whereHas('user.cliente', fn($query) => $query->where('fase_cliente', $fase));
                            });                       
                    }),
            ])
            ->deferFilters()
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
                ->visible(function () {
                    if(Helpers::isSuperAdmin())
                        return true;
                }),
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
            'index' => Pages\ListAsignacions::route('/'),
            // 'create' => Pages\CreateAsignacion::route('/create'),
            // 'edit' => Pages\EditAsignacion::route('/{record}/edit'),
        ];
    }
}
