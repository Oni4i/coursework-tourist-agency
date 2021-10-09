<?php


namespace App\Entity\User;


interface UserRolesInterface
{
    const ROLE_USER  = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    const ROLES = [
        self::ROLE_USER,
        self::ROLE_ADMIN,
    ];
}