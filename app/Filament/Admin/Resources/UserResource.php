<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Filament\Admin\Resources\UserResource\RelationManagers\AsignacionRelationManager;
use App\Filament\Admin\Resources\UserResource\RelationManagers\CuentaClienteRelationManager;
use App\Filament\Admin\Resources\UserResource\RelationManagers\SeguimientosRelationManager;
use App\Filament\Admin\Resources\UserResource\RelationManagers\UserMovimientosRelationManager;
use App\Helpers\Helpers;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use NunoMaduro\Collision\Adapters\Phpunit\State;

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
           
            ->query(function () {
                $query = User::query();
            
                if (Helpers::isAsesor()) {
                    $query->whereHas('roles', function ($query) {
                        $query->where('name', 'cliente');
                    })
                    ->whereHas('asignacion', function ($query) {
                        $query->whereHas('asesor.user', function ($query) {
                            $query->where('name', auth()->user()->name);
                        });
                    });
                    // ->whereHas('asignacion.id', function($query){
                    //     $query->where('estado_asignacion', true);
                    // }); // Filtrar solo asignaciones activas
                }
            
                return $query;
            })
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->tooltip('Haga click para copiar')
                    ->searchable()
                    ->visible(function () {
                        if (Helpers::isSuperAdmin()) {
                            return true;
                        }
                    }),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Rol asignado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.estado_cliente')
                    ->label('Estado cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.fase_cliente')
                    ->label('Fase cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asignacion.estado_asignacion')
                    ->label('Estado asignacion')
                    ->badge()
                    ->searchable()
                    ->color(function ($state) {
                        return match ($state) {
                            true => 'success',
                            false => 'danger',
                        };
                    })
                    ->formatStateUsing(function ($state) {
                        return $state ? 'Activa' : 'Inactiva';
                    }),
                Tables\Columns\TextColumn::make('asignacion.asesor.user.name')
                    ->label('Asesor asignado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asignacion.id')
                    ->label('asignacion id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.celular')
                    ->copyable()
                    ->label('Celular')
                    ->tooltip('Haga click para copiar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cliente.updated_at')
                    ->dateTime()
                    ->label('ultima actualización')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Fecha de creacion')
                    ->sortable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Rol asignado')
                    ->relationship('roles', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                /**
                 *  ASIGNACION MASIVA
                 */
                BulkAction::make('Asignar asesor')
                    ->color('primary')
                    ->icon('heroicon-s-arrows-right-left')
                    ->form([
                        Select::make('asesor_id')
                            ->relationship('asesor', 'id') // Define la relación y la clave foránea
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn(Model $record) => $record->user->name)
                            ->default(null),
                    ])
                    ->action(function (array $data, Collection $records) {
                        $records->each->assignNewAsesor($data['asesor_id']);
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                        Notification::make()
                            ->title('Asesor asignado con éxito')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->visible(function(){
                        if(Helpers::isSuperAdmin()){
                            return true;
                        }
                    }),
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
                    })
                    ->after(function () {
                        // NOTIFICAR QUE LA ASIGNACION FUE EXITOSA
                        Notification::make()
                            ->title('Roles actualizado con éxito')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->visible(function(){
                        if(Helpers::isSuperAdmin()){
                            return true;
                        }
                    }),
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
