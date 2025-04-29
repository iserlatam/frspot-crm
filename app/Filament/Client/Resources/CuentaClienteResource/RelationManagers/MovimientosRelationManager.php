<?php

namespace App\Filament\Client\Resources\CuentaClienteResource\RelationManagers;

use App\Helpers\Helpers;
use App\Models\CuentaCliente;
use App\Models\Movimiento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MovimientosRelationManager extends RelationManager
{
    protected static string $relationship = 'movimientos';

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
                            ])
                            ->label('Tipo de solicitud')
                            ->required(),
                        Forms\Components\TextInput::make('ingreso')
                            ->columnSpan(2)
                            ->required()
                            ->prefix('$')
                            ->numeric(),
                        Forms\Components\TextInput::make('cuenta_cliente_id')
                            ->label('Número de cuenta del cliente')
                            ->readOnly()
                            ->columnSpan(3)
                            ->default(function () {
                                return $this->getOwnerRecord()->id;
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
            ->searchPlaceholder('Buscar por número de radicado')
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
                    }),
                Tables\Columns\TextColumn::make('cuentaCliente.sistema_pago')
                    ->label('Sistema de pago asociado')
                    ->formatStateUsing(function ($record) {
                        if (isset($record->cuentaCliente)) {
                            return $record->cuentaCliente->sistema_pago;
                        }
                        return 'No Aplica';
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
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
                        'b' => 'Bono',
                        'g' => 'Ganancia',
                        'p' => 'Perdida',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Helpers::renderReloadTableAction()
            ], HeaderActionsPosition::Bottom);
    }
}
