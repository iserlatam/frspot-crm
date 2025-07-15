<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SeguimientoKpiDiarioResource\Pages;
use App\Filament\Admin\Resources\SeguimientoKpiDiarioResource\RelationManagers;
use App\Models\SeguimientoKpiDiario;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'KPIs';

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
            ->columns([
                Tables\Columns\TextColumn::make('fecha_kpi')
                    ->date()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('nombre_asesor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rol_asesor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_asesor')
                    ->searchable(),
                Tables\Columns\IconColumn::make('cumplio_meta')
                    ->boolean(),
                Tables\Columns\TextColumn::make('cantidad_clientes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantidad_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('faltantes')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asesor_id')
                    ->numeric()
                    ->hidden()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
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
            'create' => Pages\CreateSeguimientoKpiDiario::route('/create'),
            'edit' => Pages\EditSeguimientoKpiDiario::route('/{record}/edit'),
        ];
    }
}
