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
            ->defaultSort('user.cliente.updated_at', 'desc')
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
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente asignado')
                    ->copyable()
                    ->tooltip('Haga click para copiar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asesor.user.name')
                    ->label('Asesor asignado')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_asignacion')
                    ->label('Estado de la asignacion')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.cliente.fase_cliente')
                    ->label('fase cliente')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada el')
                    ->date('M d/Y h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.cliente.updated_at')
                    ->label('Ultima actualizacion')
                    ->searchable()
                    ->sortable()
                    ->date('M d/Y h:i A'),
                    ])
            ->filters([
                SelectFilter::make('estado_asignacion')
                    ->options([
                        true => 'Activa',
                        false => 'Inactiva'
                    ]),
                Filter::make('created_at_day')
                    ->form([
                        Forms\Components\DatePicker::make('created_at_day')
                            ->label('Día específico')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_at_day'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', $date)
                            );
                    }),
                // Filter::make('asesor_id')
                //     ->form([
                //         Forms\Components\Select::make('asesor_id')
                //             ->relationship('asesor', 'id')
                //             ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->user->name)
                //             ->searchable()
                //             ->preload(),                        
                //     ])
                //     ->query(function (Builder $query, $data){                       
                //         return $query->when(
                //             !empty($data) && !empty($data['asesor_id']),
                //                 function ($query, $data) {
                //                     $query->where('asesor_id', $data);
                //             });                         
                //     }),
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
