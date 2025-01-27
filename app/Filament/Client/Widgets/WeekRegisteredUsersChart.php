<?php

namespace App\Filament\Client\Widgets;

use App\Helpers\Helpers;
use Filament\Widgets\ChartWidget;

class WeekRegisteredUsersChart extends ChartWidget
{
    protected static ?string $heading = 'Usuarios registrados esta semana';

    protected static ?string $description = 'Cantidad de usuarios registrados en la semana actual.';

    public static function canView(): bool
    {
        return Helpers::isSuperAdmin();
    }

    protected function getData(): array
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $users = \App\Models\User::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $dates = [];
        $counts = [];

        for ($date = $startOfWeek; $date->lte($endOfWeek); $date->addDay()) {
            $formattedDate = $date->toDateString();
            $humanReadableDate = $date->format('D, j'); // Format date to human readable format
            $dates[] = $humanReadableDate;
            $counts[] = (int) ($users[$formattedDate] ?? 0); // Convert to integer
        }

        return [
            'labels' => $dates,
            'datasets' => [
            [
                'label' => 'Usuarios registrados',
                'data' => $counts,
            ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
