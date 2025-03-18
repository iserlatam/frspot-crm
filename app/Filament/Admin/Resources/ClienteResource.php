<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ClienteResource\Pages;
use App\Filament\Admin\Resources\ClienteResource\RelationManagers;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use League\CommonMark\Extension\Table\TableSection;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $activeNavigationIcon = 'heroicon-s-arrows-right-left';

    protected static ?string $navigationGroup = 'Gestión de perfiles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Cliente::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre_completo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contador_ediciones')
                    ->searchable(),
                Tables\Columns\TextColumn::make('identificacion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_nacimiento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('genero')
                    ->getStateUsing(function ($record) {
                        if ($record->genero == 'f') {
                            return 'Femenino';
                        } else {
                            return 'Masculino';
                        }
                    }),
                Tables\Columns\TextColumn::make('pais')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ciudad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cod_postal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('celular')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->searchable(),
                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('promocion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fase_cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('origenes')
                    ->searchable(),
                Tables\Columns\TextColumn::make('infoeeuu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('caso')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_doc_subm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('activo_subm')
                    ->searchable(),
                Tables\Columns\TextColumn::make('metodo_pago')
                    ->searchable(),
                Tables\Columns\TextColumn::make('doc_soporte')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->date('M d/Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->date('M d/Y h:i A')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('eliminar contador de ediciones')
                    ->color('danger')
                    ->icon('heroicon-s-arrow-path')                
                    ->action(function(Collection $records): void {
                        $records->each(function ($cliente) {
                            $cliente->touch();  
                        });
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA OPERACIÓN FUE EXITOSA
                        Notification::make()
                            ->title('Contador de ediciones actualizado con éxito')
                            ->success()
                            ->send();
                    })
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            // 'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
