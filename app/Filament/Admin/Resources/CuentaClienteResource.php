<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CuentaClienteResource\Pages;
use App\Filament\Admin\Resources\CuentaClienteResource\RelationManagers;
use App\Filament\Admin\Resources\CuentaClienteResource\RelationManagers\MovimientosRelationManager;
use App\Filament\Admin\Resources\CuentaClienteResource\Widgets\AccountInfoWidget;
use App\Helpers\Helpers;
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

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $activeNavigationIcon = 'heroicon-s-currency-dollar';

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
            ->query(
                cuentaCliente::query()
                ->whereHas('user', function (Builder $query) {
                    $query->whereHas('roles', function ($query) {
                        $query->where('name', 'cliente');
                    });
                })
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
            ->headerActions([
                Helpers::renderReloadTableAction(),
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

    public static function getWidgets(): array
    {
        return [
            AccountInfoWidget::class,
        ];
    }
}
