<?php

namespace App\Filament\Client\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CuentaClienteRelationManager extends RelationManager
{
    protected static string $relationship = 'cuentaCliente';

    protected static ?string $modelLabel = 'Cuenta';

    protected static ?string $title = 'Informacion de la cuenta';

    protected static ?string $icon = 'heroicon-m-credit-card';

    protected $listeners = ['refresh-account-table' => 'refreshTable'];

    public function refreshTable()
    {
        // Refresca la tabla
        $this->resetTable();
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Numero de cuenta')
                    ->description(function ($record) {
                        return "Propietario: " . $record->user->cliente->nombre_completo;
                    }),
                Tables\Columns\TextColumn::make('monto_total')
                    ->label('Saldo total')
                    ->money('USD'),
                Tables\Columns\TextColumn::make('no_dep')
                    ->label('Total depositos')
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\TextColumn::make('no_retiros')
                    ->label('Total retiros')
                    ->badge()
                    ->color('secondary'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada el'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
