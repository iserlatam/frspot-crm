<?php

namespace App\Helpers;

class StatusConverter
{
    public static function formatAccountStatus($status)
    {
        return $status ? 'Activa' : 'Inactiva';
    }
}
