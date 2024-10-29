<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsesorResource\Pages;
use App\Filament\Resources\AsesorResource\RelationManagers;
use App\Models\Asesor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Components\Tab;



class AsesorResource extends Resource
{
    protected static ?string $model = Asesor::class;
    protected static ?string $ModelLabel = 'Asesor';
    protected static ?string $navigationLabel = 'Asesores';
    protected static ?string $pluralModelLabel = 'Asesores';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationParentItem = 'clientes';
    protected static ?string $navigationGroup = 'Gestión de Perfiles'; 

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;



    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre_asesor')
                    ->maxLength(50)
                    ->default(null),
                Forms\Components\TextInput::make('tipo_asesor')
                    ->maxLength(20)
                    ->default(null),
                Forms\Components\TextInput::make('secreto_asesor')
                    ->maxLength(32)
                    ->default(null),
                Forms\Components\TextInput::make('qr_estado')
                    ->maxLength(16)
                    ->default(null),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->disabled()
                    ->default(null)
                    ->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre_asesor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_asesor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('secreto_asesor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qr_estado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListAsesors::route('/'),
            'create' => Pages\CreateAsesor::route('/create'),
            'edit' => Pages\EditAsesor::route('/{record}/edit'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page ->generateNavigationItems([
            Pages\EditAsesor::class,
            Pages\ListAsesors::class,
        ]);
    }
}
