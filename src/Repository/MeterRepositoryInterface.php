<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Building;
use DateTimeImmutable;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception;

interface MeterRepositoryInterface
{
    /**
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws DBALException
     * @throws Exception
     */
    public function setInactiveStatusByBuildingAndUpdatedAt(Building $building, DateTimeImmutable $updatedAt): void;
}
