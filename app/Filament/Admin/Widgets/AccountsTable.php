<?php

namespace App\Filament\Admin\Widgets;

use App\Helpers\Helpers;
use App\Models\CuentaCliente;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Components\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class AccountsTable extends BaseWidget
{
    protected static ?string $heading = 'Cuentas de clientes';

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Helpers::isOwner();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CuentaCliente::query()
                    ->whereHas('user', function ($query){
                        $query->whereHas('roles',function($query){
                            $query->where('name','cliente');
                        });
                    })
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
                # Add two actions. One to check the account; the other to view the user.
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('check')
                    ->label('Ver cuenta')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.admin.resources.cuenta-clientes.edit', $record->id))
                    ->icon('heroicon-s-currency-dollar'),
                Tables\Actions\Action::make('view')
                    ->label('Ver cliente')
                    ->color('white')
                    ->url(fn ($record) => route('filament.admin.resources.users.edit', $record->user->id))
                    ->icon('heroicon-s-user'),
                ]),
            ], ActionsPosition::BeforeCells);
    }
}
