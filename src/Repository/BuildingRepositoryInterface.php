<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Building;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

interface BuildingRepositoryInterface
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findOneByFiasIdWithOrganization(string $fiasId): Building;
}
