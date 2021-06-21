<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Organization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class OrganizationRepository extends ServiceEntityRepository implements OrganizationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Organization::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneById(string $id): Organization
    {
        /**
         * @noinspection PhpUnnecessaryLocalVariableInspection
         *
         * @var Organization $organization
         */
        $organization =
            $this->createQueryBuilder('organization')
                ->where('organization.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getSingleResult();

        return $organization;
    }
}
