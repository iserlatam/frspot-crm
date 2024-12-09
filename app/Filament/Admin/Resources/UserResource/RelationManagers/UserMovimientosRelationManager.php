<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Models\CuentaCliente;
use App\Models\Movimiento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserMovimientosRelationManager extends RelationManager
{
    protected static string $relationship = 'cuentaMovimientos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no_radicado')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('no_radicado')
            ->columns([
                Tables\Columns\TextColumn::make('no_radicado')
                    ->searchable()
                    ->description(function ($record) {
                        if (isset($record->cuentaCliente)) {
                            return "Asociado a # de cuenta " . $record->cuentaCliente->id;
                        }
                        return 'Cuenta dada de baja';
                    }),
                Tables\Columns\TextColumn::make('tipo_st')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'd' => 'Deposito',
                            'r' => 'Retiro',
                        };
                    })
                    ->label('Tipo de solicitud'),
                Tables\Columns\TextColumn::make('est_st')
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            'a' => 'success',
                            'b' => 'warning',
                            'c' => 'danger',
                        };
                    })
                    ->label('Estado')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'a' => 'Aprobado',
                            'b' => 'Pendiente',
                            'c' => 'Rechazado',
                        };
                    }),
                Tables\Columns\TextColumn::make('ingreso')
                    ->numeric()
                    ->money('USDT')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cuentaCliente.user.cliente.nombre_completo')
                    ->label('Cliente solicitante')
                    ->formatStateUsing(function ($record) {
                        if (isset($record->cuentaCliente)) {
                            return $record->cuentaCliente->user->cliente->nombre_completo;
                        }
                        return 'No Aplica';
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('cuentaCliente.sistema_pago')
                    ->label('Sistema de pago asociado')
                    ->formatStateUsing(function ($record) {
                        if (isset($record->cuentaCliente)) {
                            return $record->cuentaCliente->sistema_pago;
                        }
                        return 'No Aplica';
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    // Tables\Actions\EditAction::make(),
                    /**
                     * APROBAR MOVIMIENTO
                     * +- INGRESO A LA CUENTA
                     */
                    Tables\Actions\Action::make('aprobar')
                        ->color('success')
                        ->icon('heroicon-o-check-badge')
                        ->visible(function ($record) {
                            $currentUser = auth()->user()->hasRole('super_admin');
                            // Caso negativo para usuarios no administradores
                            if (!$currentUser || $record->est_st === 'a') {
                                return false;
                            }

                            // Caso afirmativo
                            if ($record->est_st === 'b' || $record->est_st === 'c') {
                                return true;
                            }
                        })
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $movimiento = new Movimiento();

                            $body = [
                                // aprobar
                                'case' => 'a',
                                'total' => $record->ingreso,
                                'cuenta_cliente_id' => $record->cuenta_cliente_id,
                            ];

                            // Aprueba el movimiento
                            $record->est_st = 'a';
                            $record->save();

                            $movimiento->chargeAccount($body);
                        })->after(function () {
                            return Notification::make('aprobado')
                                ->success()
                                ->title('Este movimiento fue aprobado. Saldo cargado correctamente')
                                ->send();
                        }),
                    /**
                     * RECHAZAR MOVIMIENTO
                     * MANTIENE LA CUENTA IGUAL
                     */
                    Tables\Actions\Action::make('rechazar')
                        ->color('danger')
                        ->icon('heroicon-o-x-circle')
                        ->visible(function ($record) {
                            $currentUser = auth()->user()->hasRole('super_admin');
                            // Caso negativo para usuarios no administradores
                            if (!$currentUser || $record->est_st === 'c') {
                                return false;
                            }

                            // Caso afirmativo
                            if ($record->est_st === 'b' || $record->est_st === 'a') {
                                return true;
                            }
                        })
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            // Rechaza el movimiento
                            $record->est_st = 'c';
                            $record->save();
                        })->after(function () {
                            return Notification::make('rechazado')
                                ->danger()
                                ->title('Este movimiento fue rechazado.')
                                ->send();
                        }),
                ])
            ], ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
