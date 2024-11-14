<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Page;

class Lectura_de_precios extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.client.pages.lectura_de_precios';
    protected static ?string $title = 'Lectura de Precios';

    protected static ?string $navigationGroup = 'Educacion';
}
