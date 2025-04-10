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
use ParagonIE\ConstantTime\Hex;

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
                    if (Helpers::isAsesor() || Helpers::isTeamFTD() || Helpers::isTeamRTCN()) {

                        $query->whereHas('asesor.user', function ($query) {
                            $query->where('id', auth()->user()->id);
                        });
                        $query->whereHas('user', function ($query) {
                            $query->where('name', $this->ownerRecord->name);
                        });
                    }
                    elseif(Helpers::isCrmManager() || Helpers::isSuperAdmin()) {
                        $query->whereHas('user', function ($query) {
                            $query->where('name', $this->ownerRecord->name);
                        });
                    }
                    // dd($this->ownerRecord->id);
                    return $query;
                }
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('ID de asigancion'),
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
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Fecha de actualizacion')
                    ->date('M d/Y h:i A'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de creacion')
                    ->date('M d/Y h:i A'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(Helpers::isCrmManager() || Helpers::isSuperAdmin()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible( Helpers::isSuperAdmin()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                    ->visible(Helpers::isSuperAdmin()),
                ]),
            ]);
    }
}
