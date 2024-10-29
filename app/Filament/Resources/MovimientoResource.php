<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimientoResource\Pages;
use App\Filament\Resources\MovimientoResource\RelationManagers;
use App\Models\Movimiento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MovimientoResource extends Resource
{
    protected static ?string $model = Movimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Control de Usuarios'; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('radicado')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('tipo_solicitud')
                    ->required()
                    ->maxLength(25),
                Forms\Components\TextInput::make('monto_ingreso')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('estado_solicitud')
                    ->required()
                    ->maxLength(20)
                    ->default('pendiente'),
                Forms\Components\TextInput::make('billetera')
                    ->maxLength(250)
                    ->default(null),
                Forms\Components\TextInput::make('divisa')
                    ->maxLength(10)
                    ->default(null),
                Forms\Components\TextInput::make('sistema_pago')
                    ->maxLength(25)
                    ->default(null),
                Forms\Components\TextInput::make('comprobante_file')
                    ->maxLength(250)
                    ->default(null),
                Forms\Components\TextInput::make('motivo_rechazo')
                    ->maxLength(250)
                    ->default('ninguno'),
                Forms\Components\Select::make('cliente_id')
                    ->relationship('cliente', 'id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('radicado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_solicitud')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto_ingreso')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado_solicitud')
                    ->searchable(),
                Tables\Columns\TextColumn::make('billetera')
                    ->searchable(),
                Tables\Columns\TextColumn::make('divisa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sistema_pago')
                    ->searchable(),
                Tables\Columns\TextColumn::make('comprobante_file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('motivo_rechazo')
                    ->searchable(),
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
            'index' => Pages\ListMovimientos::route('/'),
            'create' => Pages\CreateMovimiento::route('/create'),
            'edit' => Pages\EditMovimiento::route('/{record}/edit'),
        ];
    }
}
