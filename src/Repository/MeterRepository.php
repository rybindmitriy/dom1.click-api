<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Building;
use App\Entity\Meter;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class MeterRepository extends ServiceEntityRepository implements MeterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meter::class);
    }

    /**
     * {@inheritdoc}
     */
    public function setInactiveStatusByBuildingAndUpdatedAt(Building $building, DateTimeImmutable $updatedAt): void
    {
        $sql =
            <<<'SQL'
                UPDATE meters
                SET status = 0
                WHERE building_communal_service_id IN
                      (SELECT id FROM buildings_communal_services WHERE building_id = :buildingId)
                  AND updated_at < :updatedAt
            SQL;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute(
            [
                'buildingId' => $building->getId()->toRfc4122(),
                'updatedAt'  => $updatedAt->format('Y-m-d H:i:s'),
            ]
        );
    }
}
