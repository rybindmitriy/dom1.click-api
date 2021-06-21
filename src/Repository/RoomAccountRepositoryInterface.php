<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Building;
use DateTimeImmutable;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Exception;

interface RoomAccountRepositoryInterface
{
    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     * @throws DBALException
     */
    public function setInactiveStatusByBuildingAndUpdatedAt(Building $building, DateTimeImmutable $updatedAt): void;
}
