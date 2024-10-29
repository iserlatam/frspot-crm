<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotaResource\Pages;
use App\Filament\Resources\NotaResource\RelationManagers;
use App\Models\Nota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotaResource extends Resource
{
    protected static ?string $model = Nota::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    public static ?string $navigationGroup = 'Seguimientos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('nota')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('estado_nota')
                    ->maxLength(20)
                    ->default(null),
                Forms\Components\TextInput::make('fase')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\Select::make('cliente_id')
                    ->relationship('cliente', 'id')
                    ->default(null),
                Forms\Components\Select::make('asesor_id')
                    ->relationship('asesor', 'id')
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('estado_nota')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fase')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asesor.id')
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
            'index' => Pages\ListNotas::route('/'),
            'create' => Pages\CreateNota::route('/create'),
            'edit' => Pages\EditNota::route('/{record}/edit'),
        ];
    }
}
