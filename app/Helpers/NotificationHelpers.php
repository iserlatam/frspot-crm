<?php

namespace App\Helpers;

use Filament\Notifications\Notification;

class NotificationHelpers extends ActionsHelpers
{
    // Add your methods and properties here
    public static function sendErrorNotification(string $message, string $title = 'Operacion fallida', ): Notification
    {
        return Notification::make($title)
            ->danger()
            ->title($title)
            ->body(__($message))
            ->send();
    }

    public static function sendSuccessNotification(string $message, string $title = 'Operacion exitosa'): Notification
    {
        return Notification::make($title)
            ->success()
            ->title($title)
            ->body(__($message))
            ->send();
    }

    public static function sendWarningNotification(string $message, string $title = 'Operacion exitosa'): Notification
    {
        return Notification::make($title)
            ->warning()
            ->title($title)
            ->body(__($message))
            ->send();
    }
}
