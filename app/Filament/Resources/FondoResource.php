<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FondoResource\Pages;
use App\Filament\Resources\FondoResource\RelationManagers;
use App\Models\Fondo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FondoResource extends Resource
{
    protected static ?string $model = Fondo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Movimientos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_solicitud_f')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('id_propietario')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tipo_solicitud')
                    ->required()
                    ->maxLength(25),
                Forms\Components\TextInput::make('estado')
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('sistema_pago')
                    ->maxLength(25)
                    ->default(null),
                Forms\Components\DateTimePicker::make('fecha_creado'),
                Forms\Components\TextInput::make('deposito')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('divisa')
                    ->maxLength(10)
                    ->default(null),
                Forms\Components\TextInput::make('motivo_rechazo')
                    ->maxLength(250)
                    ->default(null),
                Forms\Components\TextInput::make('comprobante')
                    ->maxLength(250)
                    ->default(null),
                Forms\Components\TextInput::make('radicado')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('billetera')
                    ->maxLength(250)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_solicitud_f')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_propietario')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_solicitud')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sistema_pago')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_creado')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deposito')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('divisa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('motivo_rechazo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('comprobante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('radicado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billetera')
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
            'index' => Pages\ListFondos::route('/'),
            'create' => Pages\CreateFondo::route('/create'),
            'edit' => Pages\EditFondo::route('/{record}/edit'),
        ];
    }
}
