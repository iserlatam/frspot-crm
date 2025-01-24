<?php

namespace App\Helpers;

class Helpers extends NotificationHelpers
{
    // Add your helper methods here
    public static function getCurrentUserRole(): string | array | null
    {
        return auth()->user()->roles->first()->name;
    }

    public static function isSuperAdmin(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }

    public static function isMaster(): bool
    {
        return auth()->user()->hasRole('master');
    }

    public static function isAsesor(): bool
    {
        return auth()->user()->hasRole('asesor');
    }

    public static function isOwner(): bool
    {
        return auth()->user()->hasRole(['super_admin','master']);
    }
}
