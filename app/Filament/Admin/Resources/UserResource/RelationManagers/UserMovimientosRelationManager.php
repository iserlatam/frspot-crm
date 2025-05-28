<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Filament\Admin\Resources\MovimientoResource;
use App\Helpers\Helpers;
use App\Helpers\NotificationHelpers;
use App\Models\CuentaCliente;
use App\Models\Movimiento;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Validation\ValidationException;

class UserMovimientosRelationManager extends RelationManager
{
    public Movimiento $movimiento;

    protected static string $relationship = 'cuentaMovimientos';

    protected static ?string $modelLabel = 'Movimientos';

    protected static ?string $title = 'Movimientos asociados a esta cuenta';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(8)
                    ->schema([
                        Forms\Components\TextInput::make('no_radicado')
                            ->columnSpan(1)
                            ->required()
                            ->default(str(Movimiento::generateRadicado())->upper())
                            ->readOnly()
                            ->maxLength(50)
                            ->helperText('El número de radicado es generado automáticamente'),
                        Forms\Components\Select::make('tipo_st')
                            ->columnSpan(2)
                            ->options([
                                'd' => 'Deposito',
                                'r' => 'Retiro',
                                'b' => 'Bono',
                                'g' => 'Ganancia',
                                'p' => 'Perdida',
                            ])
                            ->label('Tipo de solicitud')
                            ->required(),
                        Forms\Components\TextInput::make('ingreso')
                            ->columnSpan(2)
                            ->required()
                            ->prefix('$')
                            ->numeric()
                            ->live()
                            ->helperText(function (Get $get) {
                                $ingreso = $get('ingreso');
                                $tipo = $get('tipo_st');
                                if (($tipo === 'r') && ($ingreso > $this->getOwnerRecord()->cuentaCliente->monto_total || $this->getOwnerRecord()->cuentaCliente->monto_total == 0)) {
                                    return 'Saldo insuficiente';
                                } else {
                                    return 'Saldo disponible: ' . $this->getOwnerRecord()->cuentaCliente->monto_total;
                                }
                            }),
                        Forms\Components\TextInput::make('cuenta_cliente_id')
                            ->label('Número de cuenta del cliente')
                            ->readOnly()
                            ->columnSpan(3)
                            ->default(function () {
                                return $this->getOwnerRecord()->cuentaCliente->id;
                            }),
                    ]),
                Forms\Components\SpatieMediaLibraryFileUpload::make('comprobante_file')
                    ->columnSpanFull()
                    ->label('Comprobante')
                    ->collection('payment_cliente_validation'),
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
                            'b' => 'Bono',
                            'g' => 'Ganancia',
                            'p' => 'Perdida',
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
                Tables\Columns\TextColumn::make('cuentaCliente.user.name')
                    ->label('Cliente solicitante')
                    ->formatStateUsing(function ($record) {
                        if (isset($record->cuentaCliente)) {
                            return $record->cuentaCliente->user->name;
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
                // ->using(function (array $data, string $model) {
                //     $cuenta = CuentaCliente::findOrFail($data['cuenta_cliente_id']);

                //     // Validar saldo antes de crear el registro
                //     if ($data['tipo_st'] == 'r' && $data['ingreso'] > $cuenta->monto_total) {
                //         // Lanza una excepción de validación con un mensaje personalizado
                //         Helpers::sendErrorNotification('No se puede realizar la solicitud, saldo insuficiente');
                //         throw ValidationException::withMessages([
                //             'ingreso' => 'Saldo insuficiente. No puedes realizar esta operación.',
                //         ]);
                //     }
                //     // Crear el modelo si la validación pasa
                //     return $model::create($data);
                // }),
                Helpers::renderReloadTableAction(),
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
                            $currentUser = Helpers::isSuperAdmin();
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
                            $body = [
                                // aprobar
                                'case' => 'a',
                                'movimiento' => $record,
                            ];
                            $record->chargeAccount($body);
                            /**
                             * ACA NO DEBE IR ESA NOTIFICACION
                             * LA VALIDACION SE HACE DESDE EL MODELO QUE ES EL QUE SE ENCARGA DE EMITIR LA NOTIFICACION
                             * CORRESPONDIENTE AL ESTADO FINAL DEL MOVIMIENTO
                             */
                            // NotificationHelpers::notifyByTipoMovimiento($record->tipo_st);
                            $this->dispatch('refresh-account-table');
                        }),
                    /**
                     * RECHAZAR MOVIMIENTO
                     * MANTIENE LA CUENTA IGUAL
                     */
                    Tables\Actions\Action::make('rechazar')
                        ->color('danger')
                        ->icon('heroicon-o-x-circle')
                        ->visible(function ($record) {
                            $currentUser = Helpers::isSuperAdmin();
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
                            $body = [
                                // aprobar
                                'case' => 'c',
                                'movimiento' => $record,
                            ];
                            $record->chargeAccount($body);
                            /**
                             * ACA NO DEBE IR ESA NOTIFICACION
                             * LA VALIDACION SE HACE DESDE EL MODELO QUE ES EL QUE SE ENCARGA DE EMITIR LA NOTIFICACION
                             * CORRESPONDIENTE AL ESTADO FINAL DEL MOVIMIENTO
                             */
                            // NotificationHelpers::notifyByTipoMovimiento($record->tipo_st);
                            $this->dispatch('refresh-account-table');
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
