<?php

namespace App\Model\SidebarNavigation;

use App\Entity\User\UserRolesInterface;

abstract class AbstractSidebarNavigation
{
    const ROUTES = [
        'user_index' => [
            'label' => 'Users',
            'icon_class' => 'fas fa-users',
        ],
        'point_index' => [
            'label' => 'Points',
            'icon_class' => 'fas fa-building',
        ],
        'customer_index' => [
            'label' => 'Customers',
            'icon_class' => 'fas fa-address-book',
        ],
        'voucher_index' => [
            'label' => 'Vouchers',
            'icon_class' => 'fas fa-suitcase',
        ],
        'order_index' => [
            'label' => 'Orders',
            'icon_class' => 'fas fa-clipboard-list',
        ],
    ];

    const ROUTES_BY_ROLE = [
        UserRolesInterface::ROLE_SUPER_ADMIN => ['*'],
        UserRolesInterface::ROLE_ADMIN => ['*'],
        UserRolesInterface::ROLE_USER => [
            'customer_index',
            'order_index'
        ],
        UserRolesInterface::ROLE_GUEST => []
    ];

    public static function getRoutesByRole(string $role): array
    {
        $routes = self::ROUTES_BY_ROLE[$role];

        if (1 === \count($routes) && $routes[0] === '*') {
            return self::ROUTES;
        }

        $items = [];

        foreach ($routes as $route) {
            $items[$route] = self::ROUTES[$route];
        }

        return $items;
    }
}