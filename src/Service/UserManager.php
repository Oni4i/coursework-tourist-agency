<?php

namespace App\Service;

use App\Entity\User\User;
use App\Entity\User\UserRolesInterface;
use App\Model\SidebarNavigation\AbstractSidebarNavigation;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class UserManager
{
    private Security $security;
    private UserRepository $userRepository;

    public function __construct(
        Security $security,
        UserRepository $userRepository
    )
    {
        $this->security         = $security;
        $this->userRepository   = $userRepository;
    }

    public function canUpdateUser(?User $user): bool
    {
        return $user
            && (
                !\in_array(UserRolesInterface::ROLE_ADMIN, $user->getRoles())
                || $user === $this->security->getUser()
            );
    }

    /**
     * Get array of allowed to change users
     *
     * @return User[]
     */
    public function getAllowedUsers(): array
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $users = $this->userRepository->getUsersByRoles($this->getAllowedToChangeRolesByUser($user));

        return \array_merge($users, [$user]);
    }

    /**
     * Get array of roles, that user can change
     */
    public function getAllowedToChangeRolesByUser(User $user): array
    {
        $roles = UserRolesInterface::ROLES_BY_HIERARCHY;

        $userSupremeRole = $user->getSupremeRole();
        $userSupremeRoleIndex = \array_search($userSupremeRole, $roles);

        return \array_slice($roles, $userSupremeRoleIndex + 1);
    }

    /**
     * Get navigation items for sidebar (maybe later I will improve this)
     */
    public function getNavigationItems(): array
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return AbstractSidebarNavigation::getRoutesByRole($user ? $user->getSupremeRole() : UserRolesInterface::ROLE_GUEST);
    }
}