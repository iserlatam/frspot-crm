<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Filament\Admin\Resources\AsignacionResource;
use App\Helpers\Helpers;
use App\Models\Asignacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AsignacionRelationManager extends RelationManager
{
    protected static string $relationship = 'asignacion';

    public function form(Form $form): Form
    {
        return AsignacionResource::form($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->query(
                function () {
                    $query = Asignacion::query();

                    if (!Helpers::isSuperAdmin()) {
                        $query->where([
                            ['user_id', $this->ownerRecord->id],
                            ['asesor_id', auth()->user()->id]
                        ]);
                    }

                    // dd($this->ownerRecord->id);
                    return $query;
                }
            )
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente asignado'),
                Tables\Columns\TextColumn::make('asesor.user.name')
                    ->label('Asesor asignado'),
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
            ])
            ->filters([
                //
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
