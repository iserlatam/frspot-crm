<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AsignacionResource\Pages;
use App\Filament\Admin\Resources\AsignacionResource\RelationManagers;
use App\Models\Asignacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AsignacionResource extends Resource
{
    protected static ?string $model = Asignacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationLabel = 'Asignaciones';

    protected static ?string $breadcrumb = 'Asignaciones';

    protected static ?string $modelLabel = 'Asignaciones';

    protected static ?string $activeNavigationIcon = 'heroicon-s-arrows-right-left';

    protected static ?string $navigationGroup = 'Gestión de perfiles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('cliente')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('asesor_id')
                    ->relationship('asesor', 'id') // Define la relación y la clave foránea
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->user->name)
                    ->default(null),
                Forms\Components\Toggle::make('estado_asignacion')
                    ->helperText('Estado actual de la asignacion')
                    ->label(function ($state) {
                        return $state ? 'Activo' : 'Inactivo';
                    })
                    ->live()
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->sortable(),
                Tables\Columns\TextColumn::make('asesor.id')
                    ->label('Asesor')
                    ->sortable(),
                Tables\Columns\IconColumn::make('estado_asignacion')
                    ->boolean(),
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
            'index' => Pages\ListAsignacions::route('/'),
            'create' => Pages\CreateAsignacion::route('/create'),
            'edit' => Pages\EditAsignacion::route('/{record}/edit'),
        ];
    }
}
