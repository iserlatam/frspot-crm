<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SeguimientoResource\Pages;
use App\Filament\Admin\Resources\SeguimientoResource\RelationManagers;
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

    protected static ?string $navigationGroup = 'Cuentas y movimientos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('descripción')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('estado')
                    ->maxLength(20)
                    ->default(null),
                Forms\Components\TextInput::make('fase')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(null),
                Forms\Components\Select::make('asesor_id')
                    ->relationship('asesor', 'id') // Define la relación y la clave foránea
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->user->name)
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('descripción')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fase')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asesor.user.name')
                    ->label('Asesor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
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
            'create' => Pages\CreateSeguimiento::route('/create'),
            'edit' => Pages\EditSeguimiento::route('/{record}/edit'),
        ];
    }
}
