<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getContentTabIcon(): ?string
    {
        return 'heroicon-m-pencil-square';
    }

    public function getContentTabLabel(): ?string
    {
        return 'Editar usuario';
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
