<?php

namespace App\Helpers;

use Filament\Tables;

class ActionsHelpers
{
    // Add your helper methods here
    public static function renderReloadTableAction()
    {
        return Tables\Actions\Action::make('Refrescar tabla')
            ->icon('heroicon-s-arrow-path')
            ->action(function ($record, $livewire) {
                $livewire->getTableRecords()->fresh();
                return Helpers::sendSuccessNotification('Tabla actualizada correctamente');
            })
            ->color('secondary');
    }
}
