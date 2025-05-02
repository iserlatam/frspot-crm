<?php

namespace App\Helpers;

use Filament\Notifications\Notification;

class NotificationHelpers extends ActionsHelpers
{
    // Add your methods and properties here
    public static function sendErrorNotification(string $message, string $title = 'Operacion fallida',): Notification
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
    public static function notifyByTipoMovimiento(string $tipo): void
    {
        $notificaciones = [
            'd' => ['success', 'Depósito exitoso', 'El dinero fue depositado correctamente.'],
            'g' => ['success', 'Ganancia registrada', 'La ganancia fue sumada a la cuenta.'],
            'b' => ['success', 'Bono agregado', 'Se agregó un bono al cliente.'],
            'r' => ['success', 'Retiro realizado', 'Se retiró dinero de la cuenta.'],
            'p' => ['success', 'Perdida procesada', 'La perdida de saldo se a registrado con éxito.'],
            'x' => ['warning', 'Tipo desconocido', 'Movimiento registrado sin categoría definida.'],
        ];

        [$tipoNotificacion, $titulo, $mensaje] = $notificaciones[$tipo] ?? $notificaciones['x'];

        match ($tipoNotificacion) {
            'success' => self::sendSuccessNotification($mensaje, $titulo),
            'danger'  => self::sendErrorNotification($mensaje, $titulo),
            'warning' => self::sendWarningNotification($mensaje, $titulo),
        };
    }
}
