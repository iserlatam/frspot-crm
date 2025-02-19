<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SeguimientoResource\Pages;
use App\Filament\Admin\Resources\SeguimientoResource\RelationManagers;
use App\Helpers\Helpers;
use App\Models\Asesor;
use App\Models\Seguimiento;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeguimientoResource extends Resource
{
    protected static ?string $model = Seguimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $activeNavigationIcon = 'heroicon-s-clipboard-document-list';

    protected static ?string $navigationGroup = 'Cuentas y movimientos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Seguimiento::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asesor.user.name')
                    ->label('Asesor')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('descripcion')
                    ->html(),
                Tables\Columns\TextColumn::make('user.cliente.estado_cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.cliente.fase_cliente')
                    ->label('fase')
                    ->searchable(),
                Tables\Columns\TextColumn::make('etiqueta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Creado el')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Helpers::renderReloadTableAction(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSeguimientos::route('/'),
            // 'create' => Pages\CreateSeguimiento::route('/create'),
            // 'edit' => Pages\EditSeguimiento::route('/{record}/edit'),
        ];
    }
}
