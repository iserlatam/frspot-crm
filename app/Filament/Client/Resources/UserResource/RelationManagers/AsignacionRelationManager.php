<?php

namespace App\Filament\Client\Resources\UserResource\RelationManagers;

use App\Filament\Client\Resources\AsignacionResource;
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
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('asesor.user.name')
                    ->label('Nombre del asesor'),
                Tables\Columns\TextColumn::make('estado_asignacion')
                    ->label('Estado actual de la asignacion')
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
            ]);
    }
}
