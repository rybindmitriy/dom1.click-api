<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Building;
use App\Entity\BuildingCommunalService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class BuildingCommunalServiceRepository extends ServiceEntityRepository implements BuildingCommunalServiceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuildingCommunalService::class);
    }

    public function findByBuildingWithCommunalServices(Building $building): array
    {
        /**
         * @noinspection PhpUnnecessaryLocalVariableInspection
         *
         * @var BuildingCommunalService[]
         */
        $result =
            $this->createQueryBuilder('buildingCommunalService')
                ->addSelect('communalService')
                ->join('buildingCommunalService.communalService', 'communalService')
                ->where('buildingCommunalService.building = :buildingId')
                ->setParameter('buildingId', $building->getId()->toRfc4122())
                ->getQuery()
                ->getResult();

        return $result;
    }
}
