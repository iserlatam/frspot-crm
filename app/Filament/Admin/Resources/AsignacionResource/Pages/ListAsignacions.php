<?php

namespace App\Filament\Admin\Resources\AsignacionResource\Pages;

use App\Filament\Admin\Resources\AsignacionResource;
use App\Helpers\Helpers;
use App\Models\Asignacion;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Exceptions\Halt;

class ListAsignacions extends ListRecords
{
    protected static string $resource = AsignacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->using(function (array $data, $model) {
                    // Validar que esta asignacion no exista por el user_id del cliente
                    $cliente = Asignacion::where('user_id', $data['user_id'])->first();

                    if($cliente){
                        Helpers::sendErrorNotification('Ya existe una asignación para este cliente');
                        throw new Halt();
                    }

                    return $model::create($data);
                }),
        ];
    }
}
