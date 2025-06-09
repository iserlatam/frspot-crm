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

    public static function isAsesor(): bool
    {
        return auth()->user()->hasRole('asesor');
    }

    public static function isTeamFTD(): bool
    {
        return auth()->user()->hasRole('team ftd');
    }
    public static function isTeamRTCN(): bool
    {
        return auth()->user()->hasRole('team retencion');
    }

    public static function isCrmManager(): bool
    {
        return auth()->user()->hasRole(['crm junior','crm master']);
    }
    public static function isCrmJunior(): bool
    {
        return auth()->user()->hasRole('crm junior');
    }
    public static function canAccessPanel(): bool
    {
        return auth()->user()->hasRole(['super_admin','crm master','crm junior','team ftd','team retencion','asesor','leads']);
    }
}
