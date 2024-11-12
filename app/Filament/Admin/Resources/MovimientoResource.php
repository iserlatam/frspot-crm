<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MovimientoResource\Pages;
use App\Filament\Admin\Resources\MovimientoResource\RelationManagers;
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
                Forms\Components\TextInput::make('radicado')
                    ->required()
                    ->default('sajsj2gsu')
                    ->maxLength(50),
                Forms\Components\Select::make('tipo_solicitud')
                    ->options([
                        'deposito' => 'Deposito',
                        'retiro' => 'Retiro',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('monto_ingreso')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sistema_pago')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\Textarea::make('billetera')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('divisa')
                    ->maxLength(15)
                    ->default(null),
                Forms\Components\FileUpload::make('comprobante_file')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('motivo_rechazo')
                    ->maxLength(250)
                    ->default('ninguno'),
                Forms\Components\Select::make('cliente_id')
                    ->relationship('cliente', 'id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('radicado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_solicitud'),
                Tables\Columns\TextColumn::make('estado_solicitud')
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            'aprobado' => 'success',
                            'pendiente' => 'warning',
                            'rechazado' => 'danger',
                        };
                    }),
                Tables\Columns\TextColumn::make('monto_ingreso')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sistema_pago')
                    ->searchable(),
                Tables\Columns\TextColumn::make('divisa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('motivo_rechazo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.id')
                    ->numeric()
                    ->sortable(),
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
                        $record->estado_solicitud = 'aprobado';
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
                        $record->estado_solicitud = 'rechazado';
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
