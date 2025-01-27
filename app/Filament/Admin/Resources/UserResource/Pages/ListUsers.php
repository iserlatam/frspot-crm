<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Filament\Imports\UserImporter;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Collection;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make()
                ->importer(UserImporter::class)
                // ->processCollectionUsing(function (string $modelClass, Collection $collection) {
                //     $collection->each(function ($row) {
                //         // Eliminar las filas que no son necesarias
                //         unset(
                //             $row[0], // ID Cliente
                //             $row[2], // Estado
                //             $row[3], // Asesor
                //             $row[4], // Asignacion
                //             $row[8], // Fase
                //             $row[10], // Ultimo Contacto
                //             $row[11], // Ultimo Seguimiento
                //         );
                //     });
                //     return $collection;
                // }),
        ];
    }
}
