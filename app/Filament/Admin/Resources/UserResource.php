<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Filament\Admin\Resources\UserResource\RelationManagers\AsignacionRelationManager;
use App\Filament\Admin\Resources\UserResource\RelationManagers\CuentaClienteRelationManager;
use App\Filament\Admin\Resources\UserResource\RelationManagers\SeguimientosRelationManager;
use App\Filament\Admin\Resources\UserResource\RelationManagers\UserMovimientosRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationGroup;
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

    protected static ?string $activeNavigationIcon = 'heroicon-s-users';

    protected static ?string $navigationGroup = 'Control de usuarios';

    protected static ?string $modelLabel = 'Usuarios';

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
                    ->sortable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                /**
                 *  ASIGNAR ROL CLIENTE
                 */
                BulkAction::make('Asignar nuevo rol')
                    ->color('info')
                    ->icon('heroicon-s-identification')
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
                    })->deselectRecordsAfterCompletion(),
                /**
                 *  ACCIONES DE ELIMINACION DE USUARIOS
                 */
                Tables\Actions\DeleteBulkAction::make('delete'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Cuentas y movimientos
            RelationGroup::make('Cuentas y movimientos', [
                UserMovimientosRelationManager::class,
                CuentaClienteRelationManager::class,
            ])->icon('heroicon-m-arrows-up-down'),
            // Asignaciones
            RelationGroup::make('Asignaciones', [
                AsignacionRelationManager::class,
                SeguimientosRelationManager::class,
            ])->icon('heroicon-m-arrows-right-left')
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
