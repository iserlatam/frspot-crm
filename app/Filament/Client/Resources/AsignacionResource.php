<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\AsignacionResource\Pages;
use App\Filament\Client\Resources\AsignacionResource\RelationManagers;
use App\Helpers\Helpers;
use App\Models\Asignacion;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AsignacionResource extends Resource
{
    protected static ?string $model = Asignacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $activeNavigationIcon = 'heroicon-s-arrows-right-left';

    protected static ?string $navigationLabel = 'Asesores Asignados';

    protected static ?string $breadcrumb = 'Asignaciones';

    protected static ?string $modelLabel = 'Asignacion';

    protected static ?string $pluralModelLabel = 'Asignaciones';

    protected static ?string $navigationGroup = 'Sobre Mi';

    public static function canAccess(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Asignacion::query()->where('user_id', auth()->user()->id))
            ->selectable(false)
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente asignado'),
                Tables\Columns\TextColumn::make('asesor.user.name')
                    ->label('Asesor asignado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado_asignacion')
                    ->label('Estado de la asignacion')
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            true => 'success',
                            false => 'danger',
                        };
                    })
                    ->formatStateUsing(function ($state) {
                        return $state ? 'Activa' : 'Inactiva';
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada el')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                Helpers::renderReloadTableAction(),
            ], HeaderActionsPosition::Bottom)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAsignacions::route('/'),
            // 'create' => Pages\CreateAsignacion::route('/create'),
            // 'edit' => Pages\EditAsignacion::route('/{record}/edit'),
        ];
    }
}
