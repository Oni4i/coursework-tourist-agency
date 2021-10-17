<?php


namespace App\Entity\User;


interface UserRolesInterface
{
    const ROLE_USER         = 'ROLE_USER';
    const ROLE_ADMIN        = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN  = 'ROLE_SUPER_ADMIN';

    const ROLES = [
        self::ROLE_USER,
        self::ROLE_ADMIN,
        self::ROLE_SUPER_ADMIN,
    ];

    const ROLES_BY_HIERARCHY = [
        self::ROLE_SUPER_ADMIN,
        self::ROLE_ADMIN,
        self::ROLE_USER,
    ];
}