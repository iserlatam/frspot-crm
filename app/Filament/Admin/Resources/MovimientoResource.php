<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MovimientoResource\Pages;
use App\Filament\Admin\Resources\MovimientoResource\RelationManagers;
use App\Models\CuentaCliente;
use App\Models\Movimiento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MovimientoResource extends Resource
{
    protected static ?string $model = Movimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Cuentas y movimientos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no_radicado')
                    ->required()
                    ->default(str(Movimiento::generateRadicado())->upper())
                    ->readOnly()
                    ->maxLength(50),
                Forms\Components\Select::make('tipo_st')
                    ->options([
                        'd' => 'Deposito',
                        'r' => 'Retiro',
                    ])
                    ->label('Tipo de solicitud')
                    ->required(),
                Forms\Components\TextInput::make('ingreso')
                    ->required()
                    ->prefix('$')
                    ->numeric(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('comprobante_file')
                    ->columnSpanFull()
                    ->label('Comprobante')
                    ->collection('payment_cliente_validation'),
                Forms\Components\TextInput::make('razon_rechazo')
                    ->maxLength(250)
                    ->default('ninguno'),
                Forms\Components\Select::make('cuenta_cliente_id')
                    ->relationship('cuentaCliente', 'id')
                    ->label('# de cuenta del cliente')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_radicado')
                    ->searchable()
                    ->description(function ($record) {
                        return "Asociado a # de cuenta " . $record->cuentaCliente->id;
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('cuentaCliente.sistema_pago')
                    ->label('Sistema de pago asociado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('razon_rechazo')
                    ->searchable(),
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
                Tables\Actions\Action::make('aprobar')
                    ->color('success')
                    ->action(function ($record) {
                        $record->est_st = 'a';
                        $record->save();
                    })->after(function () {
                        return Notification::make('aprobado')
                            ->success()
                            ->title('Este movimiento fue aprobado. Saldo cargado correctamente')
                            ->send();
                    }),
                Tables\Actions\Action::make('rechazar')
                    ->color('danger')
                    ->action(function ($record) {
                        $record->est_st = 'c';
                        $record->save();
                    })->after(function () {
                        return Notification::make('rechazado')
                            ->danger()
                            ->title('Este movimiento fue rechazado.')
                            ->send();
                    }),
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
