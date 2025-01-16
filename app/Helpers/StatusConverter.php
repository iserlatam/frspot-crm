<?php

namespace App\Helpers;

class StatusConverter
{
    public static function formatAccountStatus($status)
    {
        return $status ? 'Activa' : 'Inactiva';
    }

    public static function colorAccountStatus($status)
    {
        return $status ? 'success' : 'warning';
    }
}
