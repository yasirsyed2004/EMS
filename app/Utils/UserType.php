<?php
namespace App\Utils;

class UserType
{
    const ADMIN = 'admin';
    const SUPER_ADMIN = 'Super Admin';
    const USER = 'user';

    public static $types = [
        self::SUPER_ADMIN=>self::SUPER_ADMIN,
        self::ADMIN=>self::ADMIN,
        self::USER=>self::USER,
    ];
}
