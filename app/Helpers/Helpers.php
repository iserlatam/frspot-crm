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

    public static function isCrmManager(): bool
    {
        return auth()->user()->hasRole(['crm junior','crm master']);
    }
    public static function canAccessPanel(): bool
    {
        return auth()->user()->hasRole(['super_admin','crm master','crm junior','team','asesor']);
    }

    public static function getFaseOptions(){
        $faseOptions = [
            'Call again' => 'Call Again',
            'No answer' => 'No answer',
            'Answer' => 'Answer',
            'Interested'  => 'Interested',
            'Declined' => 'Declined',
            'Potential' => 'Potential',
            'No interested' => 'No interested',
            'Stateless'  => 'Stateless',
            'Under age' => 'Under Age',
            'Invalid number' => 'Invalid number',
            'Low potential' => 'Low Potential',
            // 'New' => 'New',
            // 'Active' => 'Active',
            // 'Recovery'  => 'Recovery',
        ];

        return $faseOptions;
    }
    public static function getEstatusOptions(){
        $faseOptions = [
            'New' => 'New',
            'Answer' => 'Answer',
            'No answer' => 'No answer',
            'Call again' => 'Call Again',
            'interested'  => 'interested',
            'Deposit' => 'Deposit',
            'Declined' => 'Declined',
            'Potential' => 'Potential',
            'Active' => 'Active',
            'No interested' => 'No interested',
            'Stateless'  => 'Stateless',
            'Recovery'  => 'Recovery',
            'Invalid number' => 'Invalid number',
            'Under age' => 'Under Age',
            'Low potential' => 'Low Potential',
        ];

        return $faseOptions;
    }

}
