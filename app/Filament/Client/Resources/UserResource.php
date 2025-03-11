<?php

namespace App\Filament\Client\Resources;

use App\Filament\Client\Resources\UserResource\Pages;
use App\Filament\Client\Resources\UserResource\RelationManagers;
use App\Filament\Client\Resources\UserResource\RelationManagers\AsignacionRelationManager;
use App\Filament\Client\Resources\UserResource\RelationManagers\CuentaClienteRelationManager;
use App\Filament\Client\Resources\UserResource\RelationManagers\SeguimientosRelationManager;
use App\Filament\Client\Resources\UserResource\RelationManagers\UserMovimientosRelationManager;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $modelLabel = 'Usuario';

    public static function canAccess(): bool
    {
        if (auth()->user()->hasRole('cliente') && auth()->user()->id == request()->is("client/users/" . auth()->user()->id . "*"))
        {
            return true;
        }

        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(User::getForm());
    }

    public static function getRelations(): array
    {
        return [
            // Cuentas y movimientos
            RelationGroup::make('Cuenta y movimientos recientes', [
                UserMovimientosRelationManager::class,
                CuentaClienteRelationManager::class,
            ])->icon('heroicon-m-arrows-up-down'),
            // Asignaciones
            // RelationGroup::make('Asignaciones', [
            //     AsignacionRelationManager::class,
            //     SeguimientosRelationManager::class,
            // ])->icon('heroicon-m-arrows-right-left')
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
