<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Control de usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(User::getForm());
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Fecha de creación')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(),
            ], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                    /**
                     *  ACCIONES DE ELIMINACION DE USUARIOS
                     */
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                    /**
                     *  ACCIONES DE ASIGNACION DE ROLES
                     */
                    Tables\Actions\BulkActionGroup::make([
                        /**
                     *  ASIGNAR ROL CLIENTE
                     */
                    ]),
                    BulkAction::make('Asignar rol')
                        ->color('primary')
                        ->icon('heroicon-o-identification')
                        ->form([
                            Select::make('role')
                                ->options([
                                    'master' => 'Master',
                                    'asesor' => 'Asesor',
                                    'cliente' => 'Cliente',
                                ])
                        ])
                        ->action(function (array $data, Collection $records) {
                            // OBTENER EL VALOR DE ROLE DESDE EL MODAL
                            $records->each->syncRoles($data['role']);
                        })->after(function () {
                            // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                            Notification::make()
                                ->title('Roles actualizado con éxito')
                                ->success()
                                ->send();
                        })->deselectRecordsAfterCompletion()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
