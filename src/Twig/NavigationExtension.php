<?php

namespace App\Twig;

use App\Service\UserManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavigationExtension extends AbstractExtension
{
    private UserManager $userManager;

    public function __construct(
        UserManager $userManager
    )
    {
        $this->userManager = $userManager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_navigation_items', [$this, 'getNavigationItems']),
        ];
    }

    public function getNavigationItems(): array
    {
        return $this->userManager->getNavigationItems();
    }
}