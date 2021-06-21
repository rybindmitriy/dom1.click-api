<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Building;
use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class RoomRepository extends ServiceEntityRepository implements RoomRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByBuildingAndExternalIdWithAccountsMetersAndCommunalServices(
        Building $building,
        string $externalId,
    ): Room {
        /**
         * @noinspection PhpUnnecessaryLocalVariableInspection
         *
         * @var Room
         */
        $result =
            $this->createQueryBuilder('room')
                ->addSelect('roomAccounts')
                ->addSelect('meters')
                ->addSelect('buildingCommunalService')
                ->addSelect('communalService')
                ->leftJoin('room.roomAccounts', 'roomAccounts')
                ->leftJoin('roomAccounts.meters', 'meters')
                ->leftJoin('meters.buildingCommunalService', 'buildingCommunalService')
                ->leftJoin('buildingCommunalService.communalService', 'communalService')
                ->where('room.building = :buildingId')
                ->andWhere('room.externalId = :externalId')
                ->setParameters(
                    [
                        'buildingId' => $building->getId()->toRfc4122(),
                        'externalId' => $externalId,
                    ]
                )
                ->getQuery()
                ->getSingleResult();

        return $result;
    }
}
