<?php

namespace App\Filament\Admin\Resources\AsesorResource\RelationManagers;

use App\Filament\Admin\Resources\AsignacionResource;
use App\Helpers\Helpers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AsignacionsRelationManager extends RelationManager
{
    protected static string $relationship = 'asignacion';

    protected static ?string $title = 'Asignaciones';

    public function isReadOnly(): bool
    {
        return Helpers::isOwner() ? false : true;
    }

    public function form(Form $form): Form
    {
        return AsignacionResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordTitleAttribute('user_id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente asignado')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.cliente.estado_cliente')
                    ->label('Estado cliente')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.cliente.fase_cliente')
                    ->label('Fase cliente')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('asesor.user.name')
                    ->label('asesor asignado')
                    ->sortable()
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
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Asignado el')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
             Filter::make('created_at_day')
                ->form([
                    Forms\Components\DatePicker::make('created_at_day')
                        ->label('Día específico')
                        ->displayFormat('d/m/Y'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_at_day'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', $date)
                        );
                }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
