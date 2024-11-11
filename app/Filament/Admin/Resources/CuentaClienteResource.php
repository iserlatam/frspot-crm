<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CuentaClienteResource\Pages;
use App\Filament\Admin\Resources\CuentaClienteResource\RelationManagers;
use App\Models\CuentaCliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CuentaClienteResource extends Resource
{
    protected static ?string $model = CuentaCliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationGroup = 'Cuentas y movimientos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('billetera')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('divisa')
                    ->maxLength(15)
                    ->default(null),
                Forms\Components\TextInput::make('monto_total')
                    ->numeric()
                    ->default(0.00),
                Forms\Components\DateTimePicker::make('ultimo_movimiento'),
                Forms\Components\TextInput::make('suma_total_depositos')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('no_depositos')
                    ->maxLength(255)
                    ->default(0),
                Forms\Components\TextInput::make('suma_total_retiros')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('no_retiros')
                    ->maxLength(255)
                    ->default(0),
                Forms\Components\Select::make('cliente_id')
                    ->relationship('cliente', 'id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Único')
                    ->searchable(),
                Tables\Columns\TextColumn::make('divisa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ultimo_movimiento')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('suma_total_depositos')
                    ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('no_depositos')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('suma_total_retiros')
                    ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('no_retiros')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('cliente.nombre_completo')
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
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCuentaClientes::route('/'),
            // 'create' => Pages\CreateCuentaCliente::route('/create'),
            // 'edit' => Pages\EditCuentaCliente::route('/{record}/edit'),
        ];
    }
}
