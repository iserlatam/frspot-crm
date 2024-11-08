<?php

namespace App\Filament\Admin\Resources\AsesorResource\Pages;

use App\Filament\Admin\Resources\AsesorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsesor extends EditRecord
{
    protected static string $resource = AsesorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
