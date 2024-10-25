<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Filament\Resources\ClienteResource\RelationManagers;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('identificacion')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('nombre')
                    ->maxLength(150)
                    ->default(null),
                Forms\Components\TextInput::make('direccion')
                    ->maxLength(250)
                    ->default(null),
                Forms\Components\TextInput::make('genero')
                    ->maxLength(1)
                    ->default(null),
                Forms\Components\TextInput::make('celular')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\DatePicker::make('fecha_nacido'),
                Forms\Components\DateTimePicker::make('fecha_sistema'),
                Forms\Components\TextInput::make('estado')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('promocion')
                    ->maxLength(1)
                    ->default(null),
                Forms\Components\TextInput::make('codigo_unico')
                    ->maxLength(20)
                    ->default(null),
                Forms\Components\TextInput::make('ciudad')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('infoeeuu')
                    ->maxLength(2)
                    ->default(null),
                Forms\Components\TextInput::make('caso')
                    ->maxLength(20)
                    ->default(null),
                Forms\Components\TextInput::make('tipo_doc_subm')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('activo_subm')
                    ->maxLength(250)
                    ->default(null),
                Forms\Components\TextInput::make('metodoPago')
                    ->maxLength(25)
                    ->default(null),
                Forms\Components\TextInput::make('cod_postal')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('pais')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('monto_pag')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('doc_soporte')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('archivo_soporte')
                    ->maxLength(250)
                    ->default(null),
                Forms\Components\TextInput::make('comprobante_pag')
                    ->maxLength(250)
                    ->default(null),
                Forms\Components\TextInput::make('estado_cliente')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('fase_cliente')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('origenes')
                    ->maxLength(250)
                    ->default(null),
                Forms\Components\TextInput::make('billetera')
                    ->maxLength(250)
                    ->default(null),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('identificacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('genero')
                    ->searchable(),
                Tables\Columns\TextColumn::make('celular')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_nacido')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_sistema')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('promocion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('codigo_unico')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ciudad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('infoeeuu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('caso')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_doc_subm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('activo_subm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('metodoPago')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cod_postal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pais')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto_pag')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('doc_soporte')
                    ->searchable(),
                Tables\Columns\TextColumn::make('archivo_soporte')
                    ->searchable(),
                Tables\Columns\TextColumn::make('comprobante_pag')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fase_cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('origenes')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billetera')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
