<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteFondoResource\Pages;
use App\Filament\Resources\ClienteFondoResource\RelationManagers;
use App\Models\ClienteFondo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClienteFondoResource extends Resource
{
    protected static ?string $model = ClienteFondo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('monto_total')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('total_depositos')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('total_retiros')
                    ->numeric()
                    ->default(null),
                Forms\Components\Select::make('cliente_id')
                    ->relationship('cliente', 'id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('monto_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_depositos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_retiros')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cliente.id')
                    ->numeric()
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
            'index' => Pages\ListClienteFondos::route('/'),
            'create' => Pages\CreateClienteFondo::route('/create'),
            'edit' => Pages\EditClienteFondo::route('/{record}/edit'),
        ];
    }
}
