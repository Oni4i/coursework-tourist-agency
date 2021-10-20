<?php

namespace App\Repository;

use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param array $roles
     *
     * @return User[]
     */
    public function getUsersByRoles(array $roles): array
    {
        $whereRoleCondition = [];

        foreach ($roles as $role) {
            $whereRoleCondition[] = \sprintf('JSON_CONTAINS(u.roles, :%s) = 1', $role);
        }

        $encodedRoles   = \array_map(function ($role) { return \json_encode($role); }, $roles);
        $parameters     = \array_combine($roles, $encodedRoles);

        return $this->createQueryBuilder('u')
            ->where(join(' OR ', $whereRoleCondition))
            ->setParameters($parameters)
            ->getQuery()
            ->getResult();
    }
}
