<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Filament\Imports\UserImporter;
use App\Helpers\Helpers;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Support\Collection;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make()
              ->importer(UserImporter::class)
              ->visible(fn()=>Helpers::isSuperAdmin())
        ];
    }
    // protected function paginateTableQuery(Builder $query): Paginator
    // {
    //     // getTableRecordsPerPage() viene del Resource: 5, 25, 50, 100 o 'all'
    //     $perPage = $this->getTableRecordsPerPage() === 'all'
    //         ? $query->count()                    // si pide "all", usa COUNT para saber límite
    //         : $this->getTableRecordsPerPage();   // si pide un número, úsalo directamente

    //     // simplePaginate() omite el COUNT(*) total en la cabecera
    //     return $query->simplePaginate($perPage);
    // }
}
