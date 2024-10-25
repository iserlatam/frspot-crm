<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlantillaCorreoResource\Pages;
use App\Filament\Resources\PlantillaCorreoResource\RelationManagers;
use App\Models\PlantillaCorreo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlantillaCorreoResource extends Resource
{
    protected static ?string $model = PlantillaCorreo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre_plantilla')
                    ->maxLength(150)
                    ->default(null),
                Forms\Components\Textarea::make('cuerpo_plantilla')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('asunto_plantilla')
                    ->maxLength(150)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre_plantilla')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asunto_plantilla')
                    ->searchable(),
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPlantillaCorreos::route('/'),
            'create' => Pages\CreatePlantillaCorreo::route('/create'),
            'edit' => Pages\EditPlantillaCorreo::route('/{record}/edit'),
        ];
    }
}
