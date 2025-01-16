<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AsesorResource\Pages;
use App\Filament\Admin\Resources\AsesorResource\RelationManagers;
use App\Filament\Admin\Resources\AsesorResource\RelationManagers\AsignacionsRelationManager;
use App\Filament\Admin\Resources\AsesorResource\RelationManagers\UserRelationManager;
use App\Models\Asesor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AsesorResource extends Resource
{
    protected static ?string $model = Asesor::class;

    protected static ?string $modelLabel = 'Asesor';

    protected static ?string $navigationLabel = 'Asesores';

    protected static ?string $pluralModelLabel = 'Asesores';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $activeNavigationIcon = 'heroicon-s-user-group';

    protected static ?string $navigationGroup = 'Gestión de perfiles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tipo_asesor')
                    ->options([
                        "crm" => "CRM",
                        "calidad" => "CALIDAD",
                        "ftd" => "FTD",
                        "retencion" => "RETENCION",
                        "monitor" => "MONITOR",
                    ]),
                Forms\Components\Select::make('user_id')
                    ->relationship('userWithRoleAsesor', "name")
                    ->label('Usuario a asignar')
                    ->helperText('Solo se mostraran los usuarios con un rol asignado como ASESOR')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario asignado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_asesor')
                    ->formatStateUsing(function ($state) {
                        return str($state)->upper();
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tipo_asesor')
                    ->options([
                        "crm" => "CRM",
                        "calidad" => "CALIDAD",
                        "ftd" => "FTD",
                        "retencion" => "RETENCION",
                        "monitor" => "MONITOR",
                    ]),
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
            AsignacionsRelationManager::class,
            UserRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAsesors::route('/'),
            // 'create' => Pages\CreateAsesor::route('/create'),
            'edit' => Pages\EditAsesor::route('/{record}/edit'),
        ];
    }
}
