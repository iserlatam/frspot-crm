<?php

namespace App\Helpers;

use Filament\Notifications\Notification;

class NotificationHelpers extends ActionsHelpers
{
    // Add your methods and properties here
    public static function sendErrorNotification(string $message, string $title = 'Operacion fallida'): Notification
    {
        return Notification::make($title)
            ->danger()
            ->title($title)
            ->body(__($message))
            ->send()
            ->duration(10);
    }

    public static function sendSuccessNotification(string $message, string $title = 'Operacion exitosa'): Notification
    {
        return Notification::make($title)
            ->success()
            ->title($title)
            ->body(__($message))
            ->send();
    }

    public static function sendFailedImportNotification( $message, string $title = 'Error de importacion'): Notification
    {
        return Notification::make($title)
            ->danger()
            ->title($title)
            ->body(__($message))
            ->send();
    }

    public static function sendNotificationlogic($message, string $title = 'Advertencia'): Notification
    {
        return self::buildNotification($message, $title, 'warning');
    }

    /**
     * Método interno para construir notificaciones consistentes
     */
    protected static function buildNotification($content, string $title, string $type): Notification
    {
        $notification = Notification::make()
            ->title($title)
            ->{$type}()
            ->persistent();

        if (is_array($content)) {
            $notification->body(self::formatArrayContent($content));
        } else {
            $notification->body(__($content));
        }

        return $notification->send();
    }

    /**
     * Formatea el contenido de un array para mostrarlo en la notificación
     */
    protected static function formatArrayContent(array $data): string
    {
        $html = '<div class="space-y-1">';

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value, JSON_PRETTY_PRINT);
            }

            $html .= sprintf(
                '<div><span class="font-medium">%s:</span> <span>%s</span></div>',
                is_int($key) ? '•' : $key,
                e($value)
            );
        }

        $html .= '</div>';

        return $html;
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
