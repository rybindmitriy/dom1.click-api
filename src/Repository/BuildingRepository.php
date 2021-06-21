<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Building;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class BuildingRepository extends ServiceEntityRepository implements BuildingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Building::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByFiasIdWithOrganization(string $fiasId): Building
    {
        /**
         * @noinspection PhpUnnecessaryLocalVariableInspection
         *
         * @var Building $building
         */
        $building =
            $this->createQueryBuilder('building')
                ->addSelect('organization')
                ->join('building.organization', 'organization')
                ->where('building.fiasId = :fiasId')
                ->setParameter('fiasId', $fiasId)
                ->getQuery()
                ->getSingleResult();

        return $building;
    }
}
