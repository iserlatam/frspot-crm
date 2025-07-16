<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SeguimientoKpiDiarioResource\Pages;
use App\Filament\Admin\Resources\SeguimientoKpiDiarioResource\RelationManagers;
use App\Helpers\Helpers;
use App\Models\SeguimientoKpiDiario;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeguimientoKpiDiarioResource extends Resource
{
    protected static ?string $model = SeguimientoKpiDiario::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $navigationLabel = 'Seguimiento Kpis';

    protected static ?string $navigationGroup = 'KPIs';

    public static function canAccess(): bool
    {
        return Helpers::isSuperAdmin() || Helpers::isCrmManager();
    }

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('asesor_id')
    //                 ->required()
    //                 ->numeric(),
    //             Forms\Components\TextInput::make('nombre_asesor')
    //                 ->required()
    //                 ->maxLength(255),
    //             Forms\Components\TextInput::make('rol_asesor')
    //                 ->required()
    //                 ->maxLength(255),
    //             Forms\Components\TextInput::make('tipo_asesor')
    //                 ->required()
    //                 ->maxLength(255),
    //             Forms\Components\DatePicker::make('fecha_kpi')
    //                 ->required(),
    //             Forms\Components\TextInput::make('cantidad_clientes')
    //                 ->required()
    //                 ->numeric(),
    //             Forms\Components\TextInput::make('cantidad_total')
    //                 ->required()
    //                 ->numeric(),
    //             Forms\Components\Toggle::make('cumplio_meta')
    //                 ->required(),
    //             Forms\Components\TextInput::make('faltantes')
    //                 ->numeric()
    //                 ->default(null),
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->query( function(){
                if(Helpers::isCrmJunior()){

                    $query = SeguimientoKpiDiario::query()
                        ->where('tipo_asesor', 'ftd');

                    return $query;
                }
                return SeguimientoKpiDiario::query();
            })
            ->defaultSort('fecha_kpi', 'desc')
            ->columns([
                
                Tables\Columns\TextColumn::make('nombre_asesor')
                    ->searchable()
                    ->label('Asesor')
                    ->tooltip(fn($record) =>$record->nombre_asesor)
                    ->limit(10),
                Tables\Columns\TextColumn::make('rol_asesor')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'asesor' => 'success',
                        'team retencion' => 'warning',
                        'team ftd' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('tipo_asesor')
                    ->label('Tipo asesor')
                    ->badge()
                    ->color(fn(String $state) => match ($state) {
                        'ftd' => 'primary',
                        'retencion' => 'success',                       
                    }) 
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => strtoupper($state))              
                    ->searchable()
                    ->sortable(fn() => Helpers::isCrmJunior() ? false : true),
                Tables\Columns\TextColumn::make('cantidad_clientes')
                    ->label('Clientes contactados')
                    ->numeric()
                    ->tooltip('Total de clientes contactados')
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('cumplio_meta')
                    ->boolean()
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('cantidad_total')
                    ->label('Seguimientos realizados')
                    ->numeric()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('faltantes')
                    ->label('Faltantes')
                    ->numeric()
                    ->tooltip('Clientes faltantes para la meta')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('fecha_kpi')
                    ->formatStateUsing(function ($state) {
                            return Carbon::parse($state)
                                ->isoFormat('ddd, DD, MMM');
                        })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asesor_id')
                    ->numeric()
                    ->hidden()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->hidden(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->hidden(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('asesor_id')
                    ->label('Asesor')
                    ->options(
                        SeguimientoKpiDiario::query()
                            ->orderBy('nombre_asesor')
                            ->pluck('nombre_asesor', 'asesor_id')
                            ->toArray()
                    )
                    ->searchable()
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('asesor_id', $data['value']);
                        }
                    }),
                Tables\Filters\Filter::make('fecha_kpi')
                    ->form([
                        Forms\Components\DatePicker::make('fecha_kpi')
                            ->label('Fecha KPI')
                            ->placeholder('Selecciona una fecha')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['fecha_kpi']) {
                            $query->whereDate('fecha_kpi', $data['fecha_kpi']);
                        }
                    }),
                Tables\Filters\SelectFilter::make('rol_asesor')
                    ->label('Rol Asesor')
                    ->options([
                        'asesor' => 'Asesor',
                        'team ftd' => 'Team FTD',
                        'team retencion' => 'Team retencion',
                    ])
                    ->searchable()
                    ->query(function(Builder $query, array $data){
                        if(!empty($data['values'])){
                            $query ->whereIn('rol_asesor',$data['values']);
                        }
                    }),
                ])->deferFilters()
            // ->actions([
            //     Tables\Actions\EditAction::make(),
            // ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListSeguimientoKpiDiarios::route('/'),
            // 'create' => Pages\CreateSeguimientoKpiDiario::route('/create'),
            // 'edit' => Pages\EditSeguimientoKpiDiario::route('/{record}/edit'),
        ];
    }
}
