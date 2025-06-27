<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\CuentaClienteResource\Pages;
use App\Filament\Client\Resources\CuentaClienteResource\RelationManagers;
use App\Filament\Client\Resources\CuentaClienteResource\RelationManagers\MovimientosRelationManager;
use App\Filament\Client\Resources\CuentaClienteResource\Widgets\AccountInfoWidget;
use App\Helpers\Helpers;
use App\Models\CuentaCliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CuentaClienteResource extends Resource
{
    protected static ?string $model = CuentaCliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $activeNavigationIcon = 'heroicon-s-currency-dollar';

    protected static ?string $navigationLabel = 'Mi Cuenta cliente';

    protected static ?string $navigationGroup = 'CUENTAS';

    protected static ?int $navigationSort = 1;

    protected static ?string $breadcrumb = 'Mi Cuenta';

    protected static ?string $modelLabel = 'Cuenta';


    public static function form(Form $form): Form
    {
        return $form
            ->schema(CuentaCliente::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                CuentaCliente::query()
                    ->where('user_id', auth()->user()->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Número de cuenta'),
                Tables\Columns\TextColumn::make('estado_cuenta')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(function (): string {
                        return 'Activa';
                    }),
                Tables\Columns\TextColumn::make('sistema_pago'),
                Tables\Columns\TextColumn::make('divisa'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.id')
                    ->label('ID del cliente'),
                Tables\Columns\TextColumn::make('monto_total')
                    ->numeric()
                    ->money('USDT')
                    ->sortable(),
            ])
            ->filters([
                // Add a select filter to filter by account state.
                Tables\Filters\SelectFilter::make('estado_cuenta')
                    ->label('Estado de la cuenta')
                    ->options([
                        true => 'Activa',
                        false => 'Inactiva',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Helpers::renderReloadTableAction(),
            ], HeaderActionsPosition::Bottom)
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

    public static function getWidgets(): array
    {
        return [
            AccountInfoWidget::class,
        ];
    }
}
