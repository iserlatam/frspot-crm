<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CuentaClienteResource\Pages;
use App\Filament\Admin\Resources\CuentaClienteResource\RelationManagers;
use App\Filament\Admin\Resources\CuentaClienteResource\RelationManagers\MovimientosRelationManager;
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

    protected static ?string $navigationLabel = 'Cuentas registradas';

    protected static ?string $breadcrumb = 'Cuentas registradas';

    protected static ?string $modelLabel = 'Cuentas registradas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(CuentaCliente::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sistema_pago')
                    ->searchable(),
                Tables\Columns\TextColumn::make('divisa')
                    ->searchable(),
                Tables\Columns\IconColumn::make('estado_cuenta')
                    ->boolean(),
                Tables\Columns\TextColumn::make('movimiento.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('monto_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sum_dep')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_dep')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sum_retiros')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_retiros')
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
            MovimientosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCuentaClientes::route('/'),
            'create' => Pages\CreateCuentaCliente::route('/create'),
            'edit' => Pages\EditCuentaCliente::route('/{record}/edit'),
        ];
    }
}
