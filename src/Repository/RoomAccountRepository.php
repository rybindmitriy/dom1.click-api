<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Building;
use App\Entity\RoomAccount;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class RoomAccountRepository extends ServiceEntityRepository implements RoomAccountRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomAccount::class);
    }

    /**
     * {@inheritdoc}
     */
    public function setInactiveStatusByBuildingAndUpdatedAt(Building $building, DateTimeImmutable $updatedAt): void
    {
        $sql =
            <<<'SQL'
                UPDATE rooms_accounts
                SET status = 0
                WHERE room_id IN
                      (SELECT id FROM rooms WHERE building_id = :buildingId)
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
