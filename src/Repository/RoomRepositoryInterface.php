<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Building;
use App\Entity\Room;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

interface RoomRepositoryInterface
{
    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findOneByBuildingAndExternalIdWithAccountsMetersAndCommunalServices(Building $building, string $externalId): Room;
}
