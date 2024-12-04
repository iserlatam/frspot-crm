<?php

namespace App\Filament\Admin\Widgets;

use App\Models\CuentaCliente;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Widgets\TableWidget as BaseWidget;

class AccountsTable extends BaseWidget
{
    protected static ?string $heading = 'Cuentas activas';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CuentaCliente::query()
                    ->where('estado_cuenta', true)
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Número de cuenta'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.id')
                    ->label('ID del cliente'),
                Tables\Columns\TextColumn::make('estado_cuenta')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(function (): string {
                        return 'Activa';
                    }),
                Tables\Columns\TextColumn::make('monto_total')
                    ->numeric()
                    ->money('USDT')
                    ->sortable(),
            ])
            ->actions([
                //
            ]);
    }
}
