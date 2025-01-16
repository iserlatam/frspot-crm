<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Page;

class Educacion extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.client.pages.educacion';

    protected static ?string $title = 'Señales';

    protected static ?string $navigationLabel = 'Señales';

    protected static ?string $navigationGroup = 'Educacion';

    protected static ?int $navigationSort = 4;
}
