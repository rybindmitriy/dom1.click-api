<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Building;
use App\Entity\CExchange;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

interface CExchangeRepositoryInterface
{
    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findLastOneByBuilding(Building $building): CExchange;

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findOneById(string $id): CExchange;

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findOneByIdWithBuilding(string $id): CExchange;
}
