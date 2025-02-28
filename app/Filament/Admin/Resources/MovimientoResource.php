<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MovimientoResource\Pages;
use App\Filament\Admin\Resources\MovimientoResource\RelationManagers;
use App\Helpers\Helpers;
use App\Models\CuentaCliente;
use App\Models\Cliente;
use App\Models\Movimiento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MovimientoResource extends Resource
{
    protected static ?string $model = Movimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $activeNavigationIcon = 'heroicon-s-banknotes';

    protected static ?string $navigationGroup = 'Cuentas y movimientos';

    /**
     *  EN ESTE ESPACIO SE USA EL METODO shouldRegisterNavigation PARA DETERMINAR SI EL USUARIO
     *  TIENE PERMISOS PARA VER EL RECURSO EN EL PANEL DE ADMINISTRACION
     *  @return bool
     */
    public static function shouldRegisterNavigation(): bool
    {
        return Helpers::isSuperAdmin();
    }

    public static function canAccess(): bool
    {
        return Helpers::isSuperAdmin();
    }

    public static function form(Form $form): Form
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
                            ])
                            ->label('Tipo de solicitud')
                            ->required(),
                        Forms\Components\TextInput::make('ingreso')
                            ->columnSpan(2)
                            ->required()
                            ->prefix('$')
                            ->numeric(),
                        Forms\Components\Select::make('cuenta_cliente_id')
                            ->columnSpan(3)
                            ->relationship('cuentaCliente', 'id', function (Builder $query) {
                                $query->whereHas('user', function ($query) {
                                    $query->whereHas('roles', function ($query) {
                                        $query->where('name', 'cliente');
                                    });
                                });
                            })
                            ->label('Número de cuenta del cliente')
                            ->searchable()
                            ->preload()
                            ->native()
                            ->required(),
                    ]),
                Forms\Components\SpatieMediaLibraryFileUpload::make('comprobante_file')
                    ->columnSpanFull()
                    ->label('Comprobante')
                    ->collection('payment_cliente_validation'),
                // Forms\Components\RichEditor::make('razon_rechazo')
                //     ->default('ninguno')
                //     ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
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
                Tables\Columns\TextColumn::make('cuentaCliente.divisa')
                    ->label('divisa')
                    ->formatStateUsing(function ($record) {
                        if (isset($record->cuentaCliente)) {
                            return $record->cuentaCliente->divisa;
                        }
                        return 'No Aplica';
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->date('M d/Y h:i A')
                    ->sortable()
            ])
            ->filters([
                SelectFilter::make('est_st')
                    ->label('Estado de la solicitud')
                    ->options([
                        'a' => 'Aprobado',
                        'b' => 'Pendiente',
                        'c' => 'Rechazado',
                    ]),
                SelectFilter::make('tipo_st')
                    ->label('Tipo de la solicitud')
                    ->options([
                        'd' => 'Deposito',
                        'r' => 'Retiro',
                        'bn' => 'Bono',
                    ]),
            ])
            ->headerActions([
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
                            $body = [
                                // aprobar
                                'case' => 'c',
                                'movimiento' => $record,
                            ];
                            $record->chargeAccount($body);
                        })
                ])
            ], ActionsPosition::BeforeColumns)
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
